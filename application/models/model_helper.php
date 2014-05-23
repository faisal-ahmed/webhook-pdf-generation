<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:56 AM
 * To change this template use File | Settings | File Templates.
 */

class model_helper  extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    function debug($debugArray){
        echo "<pre>";
        print_r($debugArray);
        echo "</pre>";
    }

    function getPost($attr) {
        $return = trim($this->input->get_post($attr, true));
        return $return;
    }

    function getRootRealPath(){
        return str_replace("system/", "", BASEPATH);
    }

    function sendSMS($number, $text) {
        $text = urlencode($text);
        try {
            $ch = curl_init("http://sms.bitbirds.com/bulksms/bulksms?username=bts-codeheaven&password=code@hea&type=0&dlr=1&destination=88$number&source=PTH&message=$text");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $response = curl_exec($ch);

            if (FALSE === $response) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }

            curl_close($ch);
        } catch (Exception $exception) {
            $response = $exception;
/*            echo 'Exception Message: ' . $exception->getMessage() . '<br/>';
            echo 'Exception Trace: ' . $exception->getTraceAsString();*/
        }

        if ($response == 1) {
            return true;
        } else {
            return false;
        }
    }
}