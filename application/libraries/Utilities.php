<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 5/26/14
 * Time: 10:33 PM
 */

$base_absolute_path = str_replace("system/", "", BASEPATH);
$shortCodeFilePath = $base_absolute_path . 'shortcodes/shortcodes.csv';

define("SHORTCODE_PREFIX", "[___");
define("SHORTCODE_SUFFIX", "___]");
define("STATIC_DIRECTORY_NAME", "static");
define("GENERAL_UPLOAD_DIRECTORY_NAME", "uploads");
define("ZOHO_OFFER_DIRECTORY_NAME", 'offers');
define("ZOHO_OFFER_DIRECTORY_PATH", $base_absolute_path . ZOHO_OFFER_DIRECTORY_NAME . "/");
define("BASE_ABSOLUTE_PATH", $base_absolute_path);
define("SHORTCODES_FILE_PATH", $shortCodeFilePath);

define("ZOHO_HISTORY_FIELD_NAME", 'Contratos__(historico)');
define("ZOHO_PUBLIC_PDF_URL_FIELD_NAME", 'Contrato__(ultimo__enviado)');
define("ZOHO_TARIFA_FIELD", 'Tarifa__de__Acceso');
define("ZOHO_RERERENCIA_FIELD", 'Referencia__TDL');
define("ZOHO_COMERCIALIZODORA_FIELD", 'Comercializadora__de__la__oferta');
define("ZOHO_CONTRACT_NAME_FIELD", 'CustomModule4__Name');
define("ZOHO_ACCOUNTID_FIELD", 'ACCOUNTID');

define("TOKEN", "html_module");
define("AUTH_TOKEN", "a7acb7c2902bb99f12cc64441704496a");
define("LEAD_MODULE", "Leads");
define("ACCOUNT_MODULE", "Accounts");
define("CONTACT_MODULE", "Contacts");
define("POTENTIAL_MODULE", "Potentials");
define("CAMPAIGN_MODULE", "Campaigns");
define("CASE_MODULE", "Cases");
define("SOLUTION_MODULE", "Solutions");
define("PRODUCT_MODULE", "Products");
define("PRICE_BOOK_MODULE", "PriceBooks");
define("QUOTE_MODULE", "Quotes");
define("INVOICE_MODULE", "Invoices");
define("SALES_ORDER_MODULE", "SalesOrders");
define("VENDOR_MODULE", "Vendors");
define("PURCHASE_ORDER_MODULE", "PurchaseOrders");
define("EVENT_MODULE", "Events");
define("TASK_MODULE", "Tasks");
define("CALL_MODULE", "Calls");
define("OFFER_MODULE", "CustomModule4");
define("OFFER_MODULE_IN_HERE", "Offer");

global $options;

$options = array(
    'binPath' => 'D:/wkhtmltopdf/bin/wkhtmltopdf',
//    'binPath' => "." . $base_absolute_path . 'htmltopdf/wkhtmltox/bin',
    'binName' => 'wkhtmltopdf',
    'tmp' => BASE_ABSOLUTE_PATH . "tmp/"
);

global $zohoModules;

$zohoModules = array(
    ACCOUNT_MODULE => 'Accounts',
    CONTACT_MODULE => 'Contacts',
    OFFER_MODULE => OFFER_MODULE_IN_HERE,
    POTENTIAL_MODULE =>'Potentials',
    VENDOR_MODULE => 'Vendors',
);