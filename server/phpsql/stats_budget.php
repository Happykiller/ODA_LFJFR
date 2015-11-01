<?php
namespace Lfjfr;

require '../header.php';
require '../vendor/autoload.php';
require '../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;
//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
// phpsql/stats_budget.php

//--------------------------------------------------------------------------
$strSql = "SELECT DISTINCT a.`date`
    FROM `vue_budget` a
    WHERE 1=1
    ORDER BY a.`date` asc
;";
$params = new OdaPrepareReqSql();
$params->sql = $strSql;
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $INTERFACE->BD_ENGINE->reqODASQL($params);

$params = new stdClass();
$params->label = "elements";
$params->retourSql = $retour;
$INTERFACE->addDataReqSQL($params);

//--------------------------------------------------------------------------
foreach ($retour->data->data as $key => $value){
    $strSql = "Select SUM(a.`mantant`) as 'solde'
        FROM `vue_budget` a
        WHERE 1=1
        AND a.`date` <= STR_TO_DATE(:myDate,'%Y-%m-%d') 
    ;";
    $params = new OdaPrepareReqSql();
    $params->sql = $strSql;
    $params->bindsValue = [
        "myDate" => $value->date
    ];
    $params->typeSQL = OdaLibBd::SQL_GET_ONE;
    $retourSub = $INTERFACE->BD_ENGINE->reqODASQL($params);

    $params = new stdClass();
    $params->label = $value->date;
    $params->retourSql = $retourSub;
    $INTERFACE->addDataReqSQL($params);
}