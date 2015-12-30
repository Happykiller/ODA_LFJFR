<?php
namespace Lfjfr;

require '../header.php';
require '../vendor/autoload.php';
require '../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
$params = new OdaPrepareReqSql();
$params->sql = "select STR_TO_DATE(a.date,'%Y-%m-%d') as 'visiteDate',  b.`code_user`, count(*) as 'nb'
    from `api_tab_statistiques_site` a, `api_tab_utilisateurs` b
    where 1=1
    and a.`id_user` = b.`id`
    GROUP BY STR_TO_DATE(a.date,'%Y-%m-%d') , a.`id_user`
    ORDER BY a.`id` DESC
    LIMIT 0, 20
;";
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $INTERFACE->BD_ENGINE->reqODASQL($params);

$INTERFACE->addDataObject($retour->data->data);