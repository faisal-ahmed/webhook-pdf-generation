<?php

require_once 'model_helper.php';

class Shortcode extends model_helper
{
    function __construct()
    {
        parent::__construct();
    }

    function create_array_2_csv_local_file(array $csv_array)
    {
        if (count($csv_array) == 0) {
            return null;
        }

        $csv = '';
        $csv_handler = fopen(SHORTCODES_FILE_PATH, 'w');
        foreach ($csv_array as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $value1 = '"' . htmlentities($value1) . '"';
                if (!$key1) $csv .= $value1;
                else $csv .= ",$value1";
            }
            $csv .= "\n";
        }

        $return = fwrite($csv_handler, $csv);
        fclose($csv_handler);

        return $return;
    }

    function getAllShortcodes()
    {
        $fp = fopen(SHORTCODES_FILE_PATH, 'r') or die("can't open file");
        $return = array();

        while ($csv_line = fgetcsv($fp)) {
            $temp = array();
            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                $temp[] = html_entity_decode(trim($csv_line[$i]));
            }
            $return[] = $temp;
        }

        fclose($fp);

        return $return;
    }

    function checkShortCodes($data, $moduleName, $fieldName){
        foreach ($data as $key => $value) {
            if ($value[0] === $moduleName && $value[1] === $fieldName) {
                return $value[2];
            }
        }

        return false;
    }

    function addShortcode(){
        $module_name = $this->getPost('zoho_modules');
        $field_name = $this->getPost('field_name');
        $allFields = $this->getAllShortcodes();

        if ( ($existingShortCode = $this->checkShortCodes($allFields, $module_name, $field_name)) !== false) {
            return "Error! The ShortCode for this field already exists which is $existingShortCode.";
        }

        $newCode = SHORTCODE_PREFIX . $module_name . "__" . str_replace(" ", "__", $field_name) . SHORTCODE_SUFFIX;

        $allFields[] = array(
            $module_name,
            $field_name,
            $newCode
        );

        if ($this->create_array_2_csv_local_file($allFields) !== false) {
            return true;
        }

        return "Server Error. Please try again later.";
    }
}

?>