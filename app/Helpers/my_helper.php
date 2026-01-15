<?php

use Config\Services;

if (!function_exists('triggerSocket')) {
    function triggerSocket($url, $data)
    {
        $client = Services::curlrequest();
        $client->post($url, [
            'json' => $data,
        ]);
    }
}   

if (!function_exists('sendEmail')) {
    function sendEmail($receiver, $subject, $body)
    {
        $email = Services::email();
        $email->setTo($receiver);
        $email->setSubject($subject);
        $email->setMessage($body);
        $email->send();
    }
}

if (!function_exists('generateOTP')) {
    function generateOTP($length = 6)
    {
        $otp = '';
        for ($i = 1; $i <= $length; $i++) {
            $otp .= strval(rand(0, 9));
        }
        return $otp;
    }
}
