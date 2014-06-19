<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:45 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'controller_helper.php';
require_once (BASEPATH . "../zoho_library/utils_zoho_request.php");

class zohoapi extends controller_helper{
    function __construct() {
        parent::__construct();
    }

    function index(){
        $security_token = $_REQUEST['token'];
        if ($security_token === TOKEN) {
            global $zohoModules;
            $id = $_REQUEST['id'];
            $potential_id = $_REQUEST['potential_id'];
            $template_name = $_REQUEST['template_name'];
            $template = $this->template_persistence->getTemplateByName($template_name);
            $offer_module = array_search(OFFER_MODULE_IN_HERE, $zohoModules);
            $potential_module = array_search(POTENTIAL_MODULE, $zohoModules);
            $mapping_array = $this->getRecordById($offer_module, $id);
            $mapping_array = array_merge($mapping_array, $this->getRecordById($potential_module, $potential_id));
            $mapping_array = array_merge($mapping_array, $this->getRecordById(ACCOUNT_MODULE, $mapping_array[$this->shortcode->buildShortCode(POTENTIAL_MODULE, ZOHO_ACCOUNTID_FIELD)]));
            $this->debug($mapping_array);

            $url = $this->savePDF($potential_id, $template['template_id'], $mapping_array);

            $data = array(
                'date' => date('d/m/Y'),
                'tarifa' => $mapping_array[$this->shortcode->buildShortCode(OFFER_MODULE, ZOHO_TARIFA_FIELD)],
                'referencia' => $mapping_array[$this->shortcode->buildShortCode(OFFER_MODULE, ZOHO_RERERENCIA_FIELD)],
                'comercializodora' => $mapping_array[$this->shortcode->buildShortCode(OFFER_MODULE, ZOHO_COMERCIALIZODORA_FIELD)],
                'contract' => $mapping_array[$this->shortcode->buildShortCode(OFFER_MODULE, ZOHO_CONTRACT_NAME_FIELD)],
                'history_field_potential' => $mapping_array[$this->shortcode->buildShortCode(POTENTIAL_MODULE, ZOHO_HISTORY_FIELD_NAME)],
                'history_field_account' => $mapping_array[$this->shortcode->buildShortCode(ACCOUNT_MODULE, ZOHO_HISTORY_FIELD_NAME)],
                'single_url_field' => $url,
            );

            $this->updateData($mapping_array[$this->shortcode->buildShortCode(POTENTIAL_MODULE, ZOHO_ACCOUNTID_FIELD)], $potential_id, $data);
        }
    }

    function getRecordById($module, $id) {
        echo $module;
        $ret = array();
        $zohoConnector = new ZohoDataSync();
        $data = $zohoConnector->getRecordById($module, $id, 2);
        $this->debug($data);
        $xml = simplexml_load_string($data);

        if (isset($xml->result->{$module}->row)) {
            foreach ($xml->result->{$module}->row as $key => $rows) {
                foreach ($rows->FL as $key2 => $value) {
                    $fieldKey = htmlentities($value['val'], null, 'UTF-8');
                    $ret[str_replace(" ", "__", $fieldKey)] = trim($value);
                }
            }
        }

        return $this->makeShortCodes($module, $ret);
    }

    function makeShortCodes($module, $data){
        $return = array();
        foreach ($data as $key => $value) {
            $tempKey = SHORTCODE_PREFIX . $module . "__" . $key . SHORTCODE_SUFFIX;
            $return[$tempKey] = $value;
        }

        $this->debug($return);

        return $return;
    }

    function updateData($account_id, $potential_id, $data) {
        $new_history = '';
        $history_potential = ($data['history_field_potential'] !== 'null') ? "{$data['history_field_potential']}\n\n" : "";
        $history_account = ($data['history_field_account'] !== 'null') ? "{$data['history_field_account']}\n\n" : "";
        $new_history .= "Sent Date: {$data['date']}, " . str_replace("__", " ", ZOHO_TARIFA_FIELD) . ": {$data['tarifa']}\n";
        $new_history .= str_replace("__", " ", ZOHO_RERERENCIA_FIELD) . ": {$data['referencia']}, " . str_replace("__", " ", ZOHO_COMERCIALIZODORA_FIELD) . ": {$data['comercializodora']}\n";
        $new_history .= "Name: {$data['contract']}, " . "PDF Contract URL: {$data['single_url_field']}\n";
        $xmlArrayForPotential = array(
            1 => array(
                str_replace("__", " ", ZOHO_HISTORY_FIELD_NAME) => ( $history_potential . $new_history ),
                str_replace("__", " ", ZOHO_PUBLIC_PDF_URL_FIELD_NAME) => $data['single_url_field'],
            ),
        );

        $zohoConnector = new ZohoDataSync();
        $response = $zohoConnector->updateRecords(POTENTIAL_MODULE, $potential_id, $xmlArrayForPotential);
        $this->debug($response);

        $xmlArrayForAccount = array(
            1 => array(
                str_replace("__", " ", ZOHO_HISTORY_FIELD_NAME) => ( $history_account . $new_history ),
            ),
        );

        $response = $zohoConnector->updateRecords(ACCOUNT_MODULE, $account_id, $xmlArrayForAccount);
        $this->debug($response);
    }
}