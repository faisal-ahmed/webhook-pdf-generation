<?php

require_once 'model_helper.php';

class Template_persistence extends model_helper
{
    function __construct()
    {
        parent::__construct();
    }

    function checkDuplicateTemplate($template_name = null){
        if ($template_name === null) {
            $template_name = $this->getPost('template_name');
        }
        return !!($this->getTemplateByName($template_name));
    }

    function getTemplateLists(){
        $ret = array();
        $query = $this->db->get('template');

        foreach ($query->result() as $row)
        {
            $ret[] = (array)$row;
        }

        return $ret;
    }

    function getTemplateByID($id){
        $query = $this->db->get_where('template', array('template_id' => $id));
        foreach ($query->result() as $row)
        {
            return (array)$row;
        }

        return false;
    }

    function getTemplateByName($templateName){
        $query = $this->db->get_where('template', array('template_name' => $templateName));
        foreach ($query->result() as $row)
        {
            return (array)$row;
        }

        return false;
    }

    function createFolderAliasFromName($folderName){
        return str_replace(" ", "_", $folderName);
    }

    function createTemplateFolder($folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $folderPath = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$folderName";

        $ret = false;
        if (!file_exists($folderPath)) {
            $ret = mkdir($folderPath, 0777, true);
        }

        return $ret;
    }

    function deleteTemplateFolder($folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $folderPath = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$folderName";
        if (file_exists($folderPath)) {
            return rmdir($folderPath);
        }
        return true;
    }

    function moveUploadedTemplateFile($fileName, $folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $oldPath = BASE_ABSOLUTE_PATH . GENERAL_UPLOAD_DIRECTORY_NAME . "/$fileName";
        $newPath = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$folderName/$fileName";

        $ret = false;
        if (file_exists($oldPath) && !file_exists($newPath)) {
            $ret = rename($oldPath, $newPath);
        }

        return $ret;
    }

    function deleteTemplateFile($fileName, $folderName, $css = false){
        $folderName = $this->createFolderAliasFromName($folderName);
        $filePath = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$folderName/$fileName";
        if ($css !== true) {
            $newFilePath = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$folderName/prepared$fileName";
            if (file_exists($newFilePath)) {
                unlink($newFilePath);
            }
        }
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }

    function addTemplate(){
        $data = array(
            'template_name' => $this->getPost('template_name'),
            'uploaded_html_file_name' => $this->getPost('uploaded_html_file_name'),
            'uploaded_css_file_name' => $this->getPost('uploaded_css_file_name'),
            'created' => time(),
        );

        $checkDuplicate = $this->checkDuplicateTemplate($data['template_name']);
        if ($checkDuplicate) {
            return "Template already exists! Please try with different template name.";
        }

        $successfullyMovedCSSFile = false;
        $successfullyMovedHTMLFile = false;
        $successfullyCreatedFolder = $this->createTemplateFolder($data['template_name']);
        if ($successfullyCreatedFolder) {
            $successfullyMovedHTMLFile = $this->moveUploadedTemplateFile($data['uploaded_html_file_name'], $data['template_name']);
            if ($successfullyMovedHTMLFile && $data['uploaded_css_file_name'] !== '') {
                $successfullyMovedCSSFile = $this->moveUploadedTemplateFile($data['uploaded_css_file_name'], $data['template_name']);
            }
        }

        if (!$successfullyCreatedFolder || !$successfullyMovedHTMLFile || ($data['uploaded_css_file_name'] !== '' && !$successfullyMovedCSSFile)) {
            $result = false;
            if ($successfullyCreatedFolder) {
                $filesDeleted = ($successfullyMovedHTMLFile) ? $this->deleteTemplateFile($data['uploaded_html_file_name'], $data['template_name']) : "";
                $filesDeleted = ($successfullyMovedCSSFile) ? $this->deleteTemplateFile($data['uploaded_css_file_name'], $data['template_name'], true) : "";
                $folderDeleted = $this->deleteTemplateFolder($data['template_name']);
                if (!$folderDeleted) {
                    $data['junk'] = 1;
                    $this->db->insert('template', $data);
                }
            }
        } else {
            $result = $this->db->insert('template', $data);
        }

        return ($result !== true) ? "Server Error! Template cannot be added now. Please try again later." : $result;
    }

    function addSubPage(){
        $uploaded_html_file_name = $this->getPost('uploaded_html_file_name');
        $template_id = $this->getPost('template_id');
        $data = $this->getTemplateByID($template_id);
        $htmlFileName = $data['uploaded_html_file_name'];
        $newHtmlFile = "$htmlFileName;$uploaded_html_file_name";

        $update = array(
            'uploaded_html_file_name' => $newHtmlFile
        );

        $successfullyMovedHTMLFile = $this->moveUploadedTemplateFile($uploaded_html_file_name, $data['template_name']);

        if (!$successfullyMovedHTMLFile) {
            $result = false;
        } else {
            $this->db->where('template_id', $template_id);
            $result = $this->db->update('template', $update);
        }

        return ($result !== true) ? "Server Error! Additional pages addition failed. Please contact administrator." : $result;
    }

    function deleteTemplate($id){
        $data = $this->getTemplateByID($id);
        $htmlFileName = explode(';', $data['uploaded_html_file_name']);
        $cssFileName = $data['uploaded_css_file_name'];
        $folderName = $data['template_name'];

        $folderDeleted = false;
        $dbRecord = false;
        $htmlFlag = true;

        foreach ($htmlFileName as $key => $value) {
            $fileDeleted = $this->deleteTemplateFile($value, $folderName);
            if ($fileDeleted === false) {
                $htmlFlag = false;
                break;
            }
        }
        $cssDeleted = ($cssFileName != '') ? $this->deleteTemplateFile($cssFileName, $folderName, true) : true;

        if ($htmlFlag && $cssDeleted) {
            $folderDeleted = $this->deleteTemplateFolder($folderName);
        }
        if ($folderDeleted) {
            $dbRecord = $this->db->delete('template', array('template_id' => $id));
        }

        return $dbRecord;
    }

    function updateTemplateStatus(){
        $id = $this->getPost('template_id');
        $htmlFile = $this->getPost('html_file_name');
        $html = $this->getPost('html', false);

        $data = $this->getTemplateByID($id);
        $template_name = $this->createFolderAliasFromName($data['template_name']);

        if ($htmlFile !== '') {
            $htmlFile = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$template_name/$htmlFile";
            $handle = fopen($htmlFile, "wb");
            $writeStatus = fwrite($handle, $html);

            $data = array(
                'updated' => time(),
            );

            $this->db->where('template_id', $id);
            $this->db->update('template', $data);

            return !!($writeStatus) ? !!$writeStatus : "Error! File cannot be updated now. Please try again later.";
        } else {
            return 'Error! No HTML template was found for that name. Please upload HTML template first.';
        }
    }

    function replaceShortCodeWithValue($html, $shortCodesValues, $existingShortCodes){
        global $checkBoxFields;
        foreach ($existingShortCodes as $key => $row) {
            if (!$key) continue;
            $value = $row[2];
            if (strpos($html, $value) !== false) {
                $replace = (isset($shortCodesValues[$value]) && $shortCodesValues[$value] !== 'null') ? $shortCodesValues[$value] : "";
                if ($replace == 'true' || $replace == 'false') {
                    if (array_key_exists($value, $checkBoxFields)) {
                        $replace = $checkBoxFields[$value][$replace];
                    }
                }
                $html = str_replace($value, $replace, $html);
            }
        }

        return $html;
    }

    function getHtmlFileLists($id = null){
        if ($id === null) {
            $id = $this->getPost('template_id');
        }
        $data = $this->getTemplateByID($id);
        return explode(';', $data['uploaded_html_file_name']);
    }

    function getHTML($id = null, $htmlFile = null){
        if ($id === null) {
            $id = $this->getPost('template_id');
        }
        if ($htmlFile === null) {
            $htmlFile = $this->getPost('html_file_name');
        }
        $ret = array();

        $data = $this->getTemplateByID($id);
        $template_name = $this->createFolderAliasFromName($data['template_name']);

        $contents = '';
        if ($htmlFile !== '') {
            $htmlFile = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$template_name/$htmlFile";
            ///////////////////HTML Load Start////////////////////
            $handle = fopen($htmlFile, "rb");
            while (!feof($handle)) {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);
            ///////////////////HTML Load End//////////////////////
        } else {
            $contents .= "<h1>Error! No HTML template was found on that name.</h1>";
        }
        $ret['html'] = $contents;
        $ret['template_id'] = $id;

        return $ret;
    }

    function preparePDF($id, $fieldReplaceMap, $existingShortCodes){
        $templateFiles = $this->getHtmlFileLists($id);
        $template = $this->getTemplateByID($id);
        $templateName = $this->createFolderAliasFromName($template['template_name']);
        foreach ($templateFiles as $key => $value) {
            $data = $this->getHTML($id, $value);
            $contents = $this->replaceShortCodeWithValue($data['html'], $fieldReplaceMap, $existingShortCodes);

            $pdfUrl = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$templateName/prepared$value";
            $handle = fopen($pdfUrl, "wb");
            $writeStatus = fwrite($handle, $contents);
        }
        $return = array(
            'folder_path' => base_url() . STATIC_DIRECTORY_NAME . "/$templateName",
            'files_name' => $template['uploaded_html_file_name'],
        );

        return $return;
    }
}

?>