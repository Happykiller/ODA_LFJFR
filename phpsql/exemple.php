<?php
//Header établie la connection à la base $connection
require("../API/php/header.php");

//Fonctions : Fonctions personnelles de l'instance
require("../php/fonctions.php");

//Mode debug
$modeDebug = false;

//Public ou privé (clé obligatoire)
$modePublic = true;

//Mode de sortie text,json,xml,csv
//Pour le text utiliser $strSorti pour la sortie.
//Pour le json $object_retour sera decoder
//Pour le xml et csv $object_retour->data["resultat"]->data doit contenir qu'un est unique array.
$modeSortie = "json";

//Liens de test
// phpsql/exemple.php?milis=123450&ctrl=ok&test=ok

//Définition des entrants
$arrayInput = array(
    "ctrl" => null,
    "test" => null
);

//Définition des entrants optionel
$arrayInputOpt = array(
    "option" => null
);

//Récupération des entrants
$arrayValeur = recupInput($arrayInput,$arrayInputOpt);

//Object retour minima
// $object_retour->strErreur string
// $object_retour->data  string
// $object_retour->statut  string
// $object_retour->id_transaction  string
// $object_retour->metro  string

//--------------------------------------------------------------------------
//EXEMPLE SELECT 1 ROW
$params = new stdClass();
$params->connection = $connection;
$params->sql = "SELECT *
    FROM `".$prefixTable."api_tab_parametres` a
    WHERE 1=1
    AND a.`param_name` = :param_name
;";
$params->bindsValue = ["param_name" => [ "value" => $arrayValeur["error"],  "type" => PDO::PARAM_STR]];
$params->typeSQL = $liboda->SQL_GET_ONE;
$params->debug = $modeDebug;
$retour = $liboda->reqODASQL($params);
$object_retour->data["resultat0"] = $retour->data;

//--------------------------------------------------------------------------
//EXEMPLE SELECT N ROWS
$params = new stdClass();
$params->connection = $connection;
$params->sql = "SELECT *
    FROM `".$prefixTable."api_tab_parametres` a
    WHERE 1=1
;";
$params->typeSQL = $liboda->SQL_GET_ALL;
$params->debug = $modeDebug;
$retour = $liboda->reqODASQL($params);
$object_retour->data["resultat"] = $retour->data;

//--------------------------------------------------------------------------
//EXEMPLE EXEC
$params = new stdClass();
$params->connection = $connection;
$params->sql = "CREATE TEMPORARY TABLE coucou (
    `idElem` int(11) NOT NULL,
    `nature` varchar(100),
    PRIMARY KEY(`idElem`)
)
    SELECT a.`id` as 'idElem', a.`param_name` as 'nature' FROM `".$prefixTable."api_tab_parametres` a
;";
$params->typeSQL = $liboda->SQL_SCRIPT;
$params->debug = $modeDebug;
$retour = $liboda->reqODASQL($params);
$object_retour->data["resultat1"] = $retour->data;

//--------------------------------------------------------------------------
//EXEMPLE INSERT 1 DATA
$params = new stdClass();
$params->connection = $connection;
$params->sql = "INSERT INTO  `coucou` (
        `idElem` ,
        `nature` 
    )
    VALUES (
        99 ,  :nature
    )
;";
$params->bindsValue = ["nature" => [ "value" => "coucou",  "type" => PDO::PARAM_STR]];
$params->typeSQL = $liboda->SQL_INSERT_ONE;
$params->debug = $modeDebug;
$retour = $liboda->reqODASQL($params);
$object_retour->data["resultat2"] = $retour->data;

//--------------------------------------------------------------------------
//EXEMPLE UPDATE
$params = new stdClass();
$params->connection = $connection;
$params->sql = "UPDATE `coucou`
    SET `nature` = 'hello'
    WHERE 1=1
    AND `idElem` = 99
;";
$params->typeSQL = $liboda->SQL_SCRIPT;
$params->debug = $modeDebug;
$retour = $liboda->reqODASQL($params);
$object_retour->data["resultat3"] = $retour->data;

//---------------------------------------------------------------------------

//Cloture de l'interface
require("../API/php/footer.php");