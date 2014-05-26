<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Faisal Ahmed <faisal.ahmed0001@gmail.com>
 */
require_once( str_replace("system/", "", BASEPATH) . 'tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("", "pt");
$pdf->AddPage();

//echo $contents;

$pdf->SetMargins(0, 0, 0);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("$template_name.pdf", 'I');