<?php

use Config\Services;

function triggerSocket($url, $data)
{
    $client = Services::curlrequest();
    $client->post($url, [
        'json' => $data,
    ]);
}

function sendEmail($receiver, $subject, $body)
{
    $email = Services::email();
    $email->setTo($receiver);
    $email->setSubject($subject);
    $email->setMessage($body);
    $email->send();
}

function generateOTP($length = 6)
{
    $otp = '';
    for ($i = 1; $i <= $length; $i++) {
        $otp .= strval(rand(0, 9));
    }
    return $otp;
}

function encode($string)
{
    return base64_encode(base64_encode($string));
}

function decode($base64)
{
    return base64_decode(base64_decode($base64));
}

function getURL($path) {
    return base_url($path);
}
