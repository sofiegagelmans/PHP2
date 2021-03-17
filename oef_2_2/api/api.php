<?php

$public_access = true;
require_once "../lib/autoload.php";

//Allow access from outside (see CORS)
//header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: 'https://gf.dev'");
header("Access-Control-Allow-Credentials 'true'");

//Allow GET, POST, PUT, DELETE, OPTIONS http methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

//Allow some types of headers
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

//Set response content type and character set
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

$parts = explode("/", $request_uri);

//zoek "api" in de uri
for ($i=0; $i<count($parts) ;$i++)
{
    if ($parts[$i] == "api")
    {
        break;
    }
}

$request_part = $parts[$i + 1];
if (count($parts) > $i + 1) {
    $id = $parts[$i + 2];
}

//GET btwcode: alle btwcodes weergeven
if ($method == "GET" and $request_part == "btwcodes")
{
    $sql = "select * from eu_btw_codes";
    $data = $container->getDBManager()->GetData($sql , 'assoc');

    //execute
    print json_encode(["msg" => 'OK', "data" => $data]);
}

//GET btwcode: één btwcode weergeven
if ($method == "GET" AND $request_part == "btwcode")
{
    $sql = "select * from eu_btw_codes where eub_id=$id";
    $data = $container->getDBManager()->GetData($sql, 'assoc');

    //execute
    print json_encode(["msg" => "OK", "data" => $data]);
}

//POST btwcode: een btwcode toevoegen
if ($method == "POST" AND $request_part == "btwcodes")
{
    $code = $_POST["code"];
    $land = $_POST["land"];
    $sql = "INSERT INTO eu_btw_codes SET eub_land='$land', eub_code='$code'";
    $data = $container->getDBManager()->ExecuteSQL($sql);

    $sql = "SELECT MAX(eub_id) FROM eu_btw_codes";
    $newitem = $container->getDBManager()->GetData($sql, 'assoc');

    //execute
    http_response_code(201);
    print json_encode(["msg" => "BTW code $code - $land aangemaakt", "eub_id" => $newitem[0]['MAX(eub_id)']]);
}

//PUT btwcode: een btwcode updaten
if ($method == "PUT" AND $request_part == "btwcode")
{
    $contents = json_decode(file_get_contents("php://input"));
    $newcode = $contents->code;
    $newland = $contents->land;

    $sql = "UPDATE eu_btw_codes SET eub_code='$newcode', eub_land='$newland' WHERE eub_id=$id";
    $data = $container->getDBManager()->ExecuteSQL($sql);

    //execute
    print json_encode(["msg" => "OK", "info" => "BTW code $newcode - $newland gewijzigd"]);
}

//DELETE btwcode: een btwcode verwijderen
if ($method == "DELETE" AND $request_part == "btwcode")
{
    $sql = "DELETE FROM eu_btw_codes where eub_id=$id";
    $data = $container->getDBManager()->ExecuteSQL($sql);

    //execute
    print json_encode(["msg" => "OK", "info" => "BTW code $id verwijderd"]);
}

//errors
if ($request_part != "btwcodes" AND $request_part != "btwcode")
{
    //execute
    print json_encode(["msg" => "Deze combinatie van Resource en Method is niet toegelaten"]);
}

?>