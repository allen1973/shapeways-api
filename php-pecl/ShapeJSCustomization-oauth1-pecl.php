#!/usr/bin/php
<?php

require "consumer_key.php";
require "access_token.php";
require "api_url_base.php";
require "error.php";

try {
    $oauth = new Oauth($consumer_key, $consumer_secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
    $oauth->enableDebug();
    $oauth->setToken($access_token, $access_secret);
} catch(OAuthException $E) {
  Error("setup exception", $E->getMessage(), null, null, $E->debugInfo, $E->getFile(), $E->getLine());
}

try {
    $text = "customText";

    $filename = "customPic.png";
    $picture = file_get_contents("../pictures/". $filename);

    $spin = "ABC123";  // CHANGEME // SPIN for a product that has already been set up as a custom maker product with one text field and one picture field
    $data = array(
      "spin" => "$spin",
      "customParam" => array(
        "text" => "$text",
        "picture" => rawurlencode(base64_encode($picture)),
        "pictureName" => $filename
      )
    );

    $data_string = json_encode($data);
    $oauth->fetch($api_url_base ."/shapejs/v1", $data_string, OAUTH_HTTP_METHOD_POST, array("Accept" => "application/json", "Content-Type" => "application/json"));
    $response = $oauth->getLastResponse();
    $json = json_decode($response);    
    if (null == $json) {
        PrintJsonLastError();
        var_dump($response);
    } else {
        print_r($json);
    }
} catch(OAuthException $E) {
  Error("fetch exception", $E->getMessage(), $oauth->getLastResponse(), $oauth->getLastResponseInfo(), $E->debugInfo, $E->getFile(), $E->getLine());
}

?>

