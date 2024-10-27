<?php
include_once("_inc/config.php");
if (_CONFIG["DEBUG"]) 
{
    ini_set('display_errors', 1);
    ini_set('display_startup_erros', 1);
    error_reporting(E_ALL);
}

if (_CONFIG["DB_PDO"]) :
    try {
        $conn = new PDO("mysql:host=" . _CONFIG["DB_SERVER"] . ";dbname=" . _CONFIG["DB_NAME"], _CONFIG["DB_USER"], _CONFIG["DB_PASS"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('ERROR: ' . $e->getMessage());
    }
endif;

function jsonError($message){
    $data = json_encode([
        "error" => true,
        "message" => $message
    ]);die($data);
}

function includePart($part)
{
    global $conn;
    include_once("_pages/_parts/{$part}.php");
}

if (isset($_GET['api'])) :
    header('Content-Type: application/json; charset=utf-8');
    $pageNameAjax = $_GET['api'];

    if (file_exists("_api/{$pageNameAjax}.php"))
        include_once("_api/{$pageNameAjax}.php");

    die();
endif;

if (isset($_GET['page'])) :
    $pageName = $_GET['page'];
    if (!file_exists("_pages/{$pageName}.php"))
        $pageName = 404;
    include_once("_pages/_parts/header.php");
    include_once("_pages/{$pageName}.php");
    include_once("_pages/_parts/footer.php");

endif;
