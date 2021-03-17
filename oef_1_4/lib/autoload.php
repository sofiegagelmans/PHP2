<?php
//print json_encode($_SERVER); exit;
$request_uri = explode("/", $_SERVER['REQUEST_URI']);
$app_root = "/" . $request_uri[1] . "/" . $request_uri[2];

require_once "connection_data.php";
//require_once "pdo.php";
require_once "html_functions.php";
require_once "form_elements.php";
require_once "sanitize.php";
require_once "validate.php";
require_once "security.php";
require_once "routing.php";
require_once "strings.php";

//models
require_once $_SERVER['DOCUMENT_ROOT'] . $app_root . "/models/City.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $app_root . "/models/User.php";

//services
require_once $_SERVER['DOCUMENT_ROOT'] . $app_root . "/services/MessageService.php";
require_once $_SERVER['DOCUMENT_ROOT'] . $app_root . "/services/DBManager.php";

session_start();

//access control
require_once "access_control.php";

//initialze MessageService
$ms = new MessageService();
$dbm = new DBManager();

//initialize $old_post
$old_post = [];

if ( key_exists( 'OLD_POST', $_SESSION ) AND is_array( $_SESSION['OLD_POST']) )
{
    $old_post = $_SESSION['OLD_POST'];
    $_SESSION['OLD_POST'] = [];
}
