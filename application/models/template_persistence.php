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

    function deleteTemplateFile($fileName, $folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $filePath = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/$folderName/$fileName";
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
                $filesDeleted = ($successfullyMovedCSSFile) ? $this->deleteTemplateFile($data['uploaded_css_file_name'], $data['template_name']) : "";
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

    function deleteTemplate($id){
        $data = $this->getTemplateByID($id);
        $htmlFileName = $data['uploaded_html_file_name'];
        $cssFileName = $data['uploaded_css_file_name'];
        $folderName = $data['template_name'];

        $folderDeleted = false;
        $dbRecord = false;

        $htmlDeleted = ($htmlFileName != '') ? $this->deleteTemplateFile($htmlFileName, $folderName) : true;
        $cssDeleted = ($cssFileName != '') ? $this->deleteTemplateFile($cssFileName, $folderName) : true;
        $sampleDeleted = $this->deleteTemplateFile('preparedPDF.html', $folderName);

        if ($htmlDeleted && $cssDeleted && $sampleDeleted) {
            $folderDeleted = $this->deleteTemplateFolder($folderName);
        }
        if ($folderDeleted) {
            $dbRecord = $this->db->delete('template', array('template_id' => $id));
        }

        return $dbRecord;
    }

    function updateTemplateStatus(){
        $id = $this->getPost('template_id');
        $html = $this->getPost('html', false);

        $data = $this->getTemplateByID($id);
        $htmlFile = $data['uploaded_html_file_name'];
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
        foreach ($existingShortCodes as $key => $row) {
            if (!$key) continue;
            $value = $row[2];
            if (strpos($html, $value) !== false) {
                $replace = (isset($shortCodesValues[$value])) ? $shortCodesValues[$value] : "";
                $html = str_replace($value, $replace, $html);
            }
        }

        return $html;
    }

    function getHTML($id = null){
        if ($id === null) {
            $id = $this->getPost('template_id');
        }
        $ret = array();
        $htmlFile = '';
        $template_name = '';
        $query = $this->db->get_where('template', array('template_id' => $id));
        foreach ($query->result() as $row)
        {
            $template_name = str_replace(" ", "_", $row->template_name);
            $htmlFile = $row->uploaded_html_file_name;
            break;
        }

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
            $contents .= "<h1>Error! No HTML template was found. Please upload HTML template first.</h1>";
        }
        $ret['html'] = $contents;
        $ret['template_name'] = $template_name;
        $ret['template_id'] = $id;

        return $ret;
    }

    function preparePDF($id, $fieldReplaceMap, $existingShortCodes){
        $data = $this->getHTML($id);
        $contents = $this->replaceShortCodeWithValue($data['html'], $fieldReplaceMap, $existingShortCodes);

        $pdfUrl = BASE_ABSOLUTE_PATH . STATIC_DIRECTORY_NAME . "/{$data['template_name']}/preparedPDF.html";
        $handle = fopen($pdfUrl, "wb");
        $writeStatus = fwrite($handle, $contents);
        $urlSend = base_url() . STATIC_DIRECTORY_NAME . "/{$data['template_name']}/preparedPDF.html";

        return ($writeStatus !== false) ? $urlSend : false;
    }
}

?>