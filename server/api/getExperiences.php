<?php
namespace Lfjfr;

require '../header.php';
require '../vendor/autoload.php';
require '../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$params->arrayInput = array("critere");
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
// api/getExperiences.php?milis=123456789&critere=java,sql

//--------------------------------------------------------------------------
$critere = strtolower ($INTERFACE->inputs["critere"]);

$tab_critere = explode(",",$critere);

$strSql = "SELECT ordre,titre_experience,commentaire_titre,debut,fin,employeur,fonction,secteur,fonctionnel,realisation,language,applicatif,develloppement,bdd
    FROM `tab_experiences` 
    WHERE 1=1";

foreach($tab_critere as $key => $val) {
    $strSql .= "
        AND (
            LOWER(titre_experience) like '%".$val."%'
            OR
            LOWER(commentaire_titre) like '%".$val."%'
            OR
            LOWER(debut) like '%".$val."%'
            OR
            LOWER(fin) like '%".$val."%'
            OR
            LOWER(employeur) like '%".$val."%'
            OR
            LOWER(fonction) like '%".$val."%'
            OR
            LOWER(secteur) like '%".$val."%'
            OR
            LOWER(fonctionnel) like '%".$val."%'
            OR
            LOWER(realisation) like '%".$val."%'
            OR
            LOWER(language) like '%".$val."%'
            OR
            LOWER(applicatif) like '%".$val."%'
            OR
            LOWER(develloppement) like '%".$val."%'
            OR
            LOWER(bdd) like '%".$val."%'
            OR
            LOWER(meta) like '%".$val."%'
        )
    ";
}

$strSql .= "
    ORDER BY ordre desc
";

$params = new OdaPrepareReqSql();
$params->sql = $strSql;
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $INTERFACE->BD_ENGINE->reqODASQL($params);

$params = new stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$INTERFACE->addDataReqSQL($params);