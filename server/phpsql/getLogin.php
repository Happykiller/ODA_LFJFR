<?php
namespace Lfjfr;
use stdClass, \Oda\OdaPrepareInterface, \Oda\OdaPrepareReqSql, \Oda\OdaLibBd;
//--------------------------------------------------------------------------
//Header
require("../API/php/header.php");
require("../php/LfjfrInterface.php");

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$params->arrayInput = array("cle");
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
// phpsql/getLogin.php?milis=123456789&cle=fuel

//--------------------------------------------------------------------------
$params = new OdaPrepareReqSql();
$params->sql = "select a.`login`
    from `api_tab_utilisateurs` a
    where 1=1 
    and a.`password` = :password
;";
$params->bindsValue = [
    "password" => $INTERFACE->inputs["cle"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $INTERFACE->BD_ENGINE->reqODASQL($params);

$params = new stdClass();
$params->retourSql = $retour;
$INTERFACE->addDataReqSQL($params);