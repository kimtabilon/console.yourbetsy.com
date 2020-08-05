<?php
/**
 * 
 * load token
 * return domain
 */
function storeToken() {
    $domain = 'https://www.yourbetsy.com/';
   /*  $domain = 'https://www.ecommercebusinessprime.com/'; */

    $userData = array("username" => "yourbetsy", "password" => "08N@8NDeWD");
    /* $userData = array("username" => "admin", "password" => "Ec0m@2020"); */
    $ch = curl_init($domain."rest/V1/integration/admin/token");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

    $token = curl_exec($ch);

    return ['domain' => $domain, 'token' => $token];
}