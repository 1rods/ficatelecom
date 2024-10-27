<?php

$URL_SITE = ($_SERVER["HTTPS"] == "on") ? "https" : "http";
$URL_SITE .= "://{$_SERVER['SERVER_NAME']}";

DEFINE("_CONFIG", [
    "URL" => $URL_SITE,
    "DEBUG" => false,
    "DB_PDO" => True,
    "DB_SERVER" => "",
    "DB_USER" => "",
    "DB_PASS" => "",
    "DB_NAME" => ""
]);

?>