<?php
namespace Lfjfr;

require '../header.php';
require '../vendor/autoload.php';
require '../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$params->arrayInput = array("key");
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
// phpsql/getLogin.php?milis=123456789&key=fuel

//--------------------------------------------------------------------------
$params = new OdaPrepareReqSql();
$params->sql = "select a.`code_user`, b.`indice`
    from `api_tab_utilisateurs` a, `api_tab_rangs` b
    where 1=1
    and a.`id_rang` = b.`id`
    and a.`password` = :password
;";
$params->bindsValue = [
    "password" => $INTERFACE->inputs["key"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $INTERFACE->BD_ENGINE->reqODASQL($params);

$params = new stdClass();
$params->retourSql = $retour;
$INTERFACE->addDataReqSQL($params);