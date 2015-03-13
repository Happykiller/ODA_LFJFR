<?php
//Config : Les informations personnels de l'instance (log, pass, etc)
require("../include/config.php");

//API Fonctions : les fonctions fournis de base par l'API
require("../API/php/fonctions.php");

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
// phpsql/stats_budget.php?milis=123450&ctrl=ok&test=ok

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
$strSql = "SELECT DISTINCT a.`date`
    FROM `".$prefixTable."vue_budget` a
    WHERE 1=1
    ORDER BY a.`date` asc
;";
$req = $connection->prepare($strSql);

$resultats = new stdClass();
$resultats->data = array();
$resultats->nombre = 0;
$object_retour->data["elements"] = $resultats;
if($req->execute()){
    $req->setFetchMode(PDO::FETCH_OBJ);
    $resultats->data = $req->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    $object_retour->data["elements"] = $resultats;
}else{
    $error = 'Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")";
    $object_retour->strErreur = $error;
}
$req->closeCursor();

//--------------------------------------------------------------------------
foreach ($object_retour->data["elements"]->data as $key => $value){
    $value->valueSolde = 0;
    $strSql = "Select SUM(a.`mantant`) as 'solde'
        FROM `".$prefixTable."vue_budget` a
        WHERE 1=1
        AND a.`date` <= STR_TO_DATE(:myDate,'%Y-%m-%d') 
    ;";
    $req = $connection->prepare($strSql);
    $req->bindValue(":myDate", $value->date, PDO::PARAM_STR);

    $rows = array();
    if($req->execute()){
        $rows = $req->fetch();
        $value->valueSolde = $rows['solde'];
    }else{
        die('Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")");
    }
    $req->closeCursor();
}

//--------------------------------------------------------------------------
if($modeDebug){
    print_r($strSql);
}

//---------------------------------------------------------------------------

//Cloture de l'interface
require("../API/php/footer.php");