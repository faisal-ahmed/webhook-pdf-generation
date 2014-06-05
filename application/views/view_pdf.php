<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Faisal Ahmed <faisal.ahmed0001@gmail.com>
 */

require_once(str_replace("system/", "", BASEPATH) . 'htmltopdf/WkHtmlToPdf.php');

global $options;
if (isset($pdfUrl) && $pdfUrl !== false) {
    $pdf = new WkHtmlToPdf($options);
    $pdf->addPage($pdfUrl);
    $pdf->addPage('http://www.google.com');
    $pdf->send();
} else {
    echo "<h1>Server Error! PDF can't be generated now. Please contact with engineer.</h1>";
}

// ... or send to client as file download
//$pdf->send('test.pdf');