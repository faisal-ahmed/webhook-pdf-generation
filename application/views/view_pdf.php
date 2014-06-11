<?php
/**
 * Created by PhpStorm.
 * User: Mohammad Faisal Ahmed <faisal.ahmed0001@gmail.com>
 */

if (isset($pdfUrl)) {
    $url = json_decode(file_get_contents(PDF_API_SERVER_URL . '?url=' . $pdfUrl . '&zoho_id=' . $template_name));
    echo '<iframe id="view_pdf" width="100%" height="100%" src="http://' . $url->url . '" ></iframe>';
} else {
    echo "<h1>Server Error! PDF can't be generated now. Please contact with engineer.</h1>";
}