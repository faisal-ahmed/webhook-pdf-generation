<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 5/26/14
 * Time: 10:33 PM
 */

$base_absolute_path = str_replace("system/", "", BASEPATH);
$zohoOfferDirectory = $base_absolute_path . 'offers/';
$shortCodeFilePath = $base_absolute_path . 'shortcodes/shortcodes.csv';

define("SHORTCODE_PREFIX", "[___");
define("SHORTCODE_SUFFIX", "___]");
define("STATIC_DIRECTORY_NAME", "static");
define("GENERAL_UPLOAD_DIRECTORY_NAME", "uploads");
define("ZOHO_OFFER_DIRECTORY_PATH", $zohoOfferDirectory);
define("BASE_ABSOLUTE_PATH", $base_absolute_path);
define("SHORTCODES_FILE_PATH", $shortCodeFilePath);

global $options;

$options = array(
    'binPath' => 'D:/wkhtmltopdf/bin/wkhtmltopdf',
//    'binPath' => "." . $base_absolute_path . 'htmltopdf/wkhtmltox/bin',
    'binName' => 'wkhtmltopdf',
    'tmp' => BASE_ABSOLUTE_PATH . "tmp/"
);

global $zohoModules;

$zohoModules = array(
    'Accounts' => 'Accounts',
    'Contacts' => 'Contacts',
    'CustomModule4' => 'Offer',
    'Potentials' =>'Potentials',
    'Vendors' => 'Vendors',
);