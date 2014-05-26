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
        $this->db->select('*');
        $this->db->from('template');
        $this->db->where('template_name', $template_name);
        if ($this->db->count_all_results() != 0) {
            return true;
        } else {
            return false;
        }
    }

    function createFolderAliasFromName($folderName){
        return str_replace(" ", "_", $folderName);
    }

    function deleteFolder($folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $folderPath = $this->getRootRealPath() . "static/$folderName";
        if (file_exists($folderPath)) {
            return rmdir($folderPath);
        }
        return false;
    }

    function deleteUploadedFile($fileName, $folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $filePath = $this->getRootRealPath() . "static/$folderName/$fileName";
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    function createNewFolder($folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $folderPath = $this->getRootRealPath() . "static/$folderName";

        $ret = false;
        if (!file_exists($folderPath)) {
            $ret = mkdir($folderPath, 0777, true);
        }

        return $ret;
    }

    function moveUploadedFile($fileName, $folderName){
        $folderName = $this->createFolderAliasFromName($folderName);
        $oldPath = $this->getRootRealPath() . "uploads/$fileName";
        $newPath = $this->getRootRealPath() . "static/$folderName/$fileName";

        $ret = false;
        if (file_exists($oldPath) && !file_exists($newPath)) {
            $ret = rename($oldPath, $newPath);
        }

        return $ret;
    }

    function addTemplate(){
        $data = array(
            'template_name' => $this->getPost('template_name'),
            'uploaded_html_file_name' => $this->getPost('uploaded_html_file_name'),
            'uploaded_css_file_name' => $this->getPost('uploaded_css_file_name'),
            'created' => time(),
        );

        $checkDuplicate = $this->checkDuplicateTemplate($data['template_name']);
        if ($checkDuplicate['status']) {
            return "Template already exists! Please try with different template name.";
        }

        $addAsJunk = false;
        $errorCSS = false;
        $errorFolder = $this->createNewFolder($data['template_name']);
        $errorHTML = $this->moveUploadedFile($data['uploaded_html_file_name'], $data['template_name']);
        if ($errorHTML && $data['uploaded_css_file_name'] !== '') {
            $errorCSS = $this->moveUploadedFile($data['uploaded_css_file_name'], $data['template_name']);
        }

        if (($errorHTML === false || $errorFolder === false) || ($data['uploaded_css_file_name'] !== '' && $errorCSS === false)) {
            $result = false;
            if ($errorHTML === false) {
                $folderDeleted = $this->deleteFolder($data['template_name']);
                if (!$folderDeleted) {
                    $addAsJunk = true;
                }
            }
            if ($errorCSS === false) {
                $fileDeleted = $this->deleteUploadedFile($data['uploaded_html_file_name'], $data['template_name']);
                $folderDeleted = $this->deleteFolder($data['template_name']);
                if (!$fileDeleted || !$folderDeleted) {
                    $addAsJunk = true;
                }
            }
        } else {
            $result = $this->db->insert('template', $data);
        }

        if ($addAsJunk) {
            $data['junk'] = 1;
            $this->db->insert('template', $data);
        }

        return ($result !== true) ? "Server Error! Template cannot be added now. Please try again later." : $result;
    }

    function deleteTemplate($id){
        $query = $this->db->get_where('template', array('template_id' => $id));
        $htmlFileName = '';
        $cssFileName = '';
        $pdfFileName = '';
        $folderName = '';

        foreach ($query->result() as $row)
        {
            $folderName = $row->template_name;
            $htmlFileName = $row->uploaded_html_file_name;
            $cssFileName = $row->uploaded_css_file_name;
            $pdfFileName = $row->uploaded_pdf_file_name;
            break;
        }

        $folderDeleted = false;
        $dbRecord = false;

        $htmlDeleted = ($htmlFileName != '') ? $this->deleteUploadedFile($htmlFileName, $folderName) : true;
        $cssDeleted = ($cssFileName != '') ? $this->deleteUploadedFile($cssFileName, $folderName) : true;
        $pdfDeleted = ($pdfFileName != '') ? $this->deleteUploadedFile($pdfFileName, $folderName) : true;

        if ($htmlDeleted && $cssDeleted && $pdfDeleted) {
            $folderDeleted = $this->deleteFolder($folderName);
        }
        if ($folderDeleted) {
            $dbRecord = $this->db->delete('template', array('template_id' => $id));
        }

        return $dbRecord;
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

    function getURL($id){
        $ret = array();
        $htmlFile = '';
        $cssFile = '';
        $template_name = '';
        $query = $this->db->get_where('template', array('template_id' => $id));
        foreach ($query->result() as $row)
        {
            $template_name = str_replace(" ", "_", $row->template_name);
            $htmlFile = $row->uploaded_html_file_name;
            $cssFile = $row->uploaded_css_file_name;
            break;
        }

        $contents = '<html>';
        if ($htmlFile !== '') {
            if ($cssFile !== '') {
                $cssFile = $this->getRootRealPath() . "static/" . $template_name . "/" . $cssFile;
                ///////////////////CSS Load Start////////////////////
                $contents .= '<style type="text/css">';
                $handle = fopen($cssFile, "rb");
                while (!feof($handle)) {
                    $contents .= fread($handle, 8192);
                }
                fclose($handle);
                $contents .= '</style>';
                ///////////////////CSS Load End////////////////////
            }

            $htmlFile = $this->getRootRealPath() . "static/" . $template_name . "/" . $htmlFile;
            ///////////////////HTML Load Start////////////////////
            $contents .= "<body>";
            $handle = fopen($htmlFile, "rb");
            while (!feof($handle)) {
                $contents .= fread($handle, 8192);
            }
            fclose($handle);
            $contents .= "</body>";
            ///////////////////HTML Load End//////////////////////
        } else {
            $contents .= "<body><h1>Error! No HTML template was found. Please upload HTML template first.</h1></body>";
        }
        $contents .= "</html>";
        $ret['html'] = $contents;
        $ret['template_name'] = $template_name;

        return $ret;
    }
}

?>