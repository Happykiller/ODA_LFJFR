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
//pour xml et csv $object_retour->data["resultat"] doit contenir qu'un est unique array
$modeSortie = "json";

//Liens de test
// phpsql/creerCompte.php?milis=123450&ctrl=ok&input_nom=Test&input_cle=cle&input_code_user=TEST&input_mail=test@mail.com&input_description=hello&input_cv=truc.pdf

//Définition des entrants
$arrayInput = array(
    "ctrl" => null,
    "input_nom" => null,
    "input_cle" => null,
    "input_code_user" => null,
    "input_mail" => null,
    "input_description" => null,
    "input_cv" => null
);

//Récupération des entrants
$arrayValeur = recupInput($arrayInput);

//Object retour minima
// $object_retour->strErreur string
// $object_retour->data  string
// $object_retour->statut  string

$object_retour->data["input_cv"] = $arrayValeur["input_cv"];

//--------------------------------------------------------------------------
$strSql = "INSERT INTO `tab_utilisateurs`
    (`login`, `password`, `code_user`, `nom`, `profile`, `description`, `montrer_aide_ihm`, `mail`, `actif`, `date_creation`) 
    VALUES 
    (:nom,:cle,:code_user,:nom,99,:description,0,:mail,1,NOW())
;";

$req = $connection->prepare($strSql);
$req->bindValue(":nom", $arrayValeur["input_nom"], PDO::PARAM_STR);
$req->bindValue(":cle", $arrayValeur["input_cle"], PDO::PARAM_STR);
$req->bindValue(":code_user", $arrayValeur["input_code_user"], PDO::PARAM_STR);
$req->bindValue(":description", $arrayValeur["input_description"], PDO::PARAM_STR);
$req->bindValue(":mail", $arrayValeur["input_mail"], PDO::PARAM_STR);

$id = 0;
if($req->execute()){
    $id = $connection->lastInsertId(); 
}else{
    $error = 'Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")";
    $object_retour->strErreur = $error;
}
$req->closeCursor();
$object_retour->data["resultatInsert"] = $id;

//--------------------------------------------------------------------------
if($modeDebug){
    $strSorti .= ($strSql);
}

//Cloture de l'interface
require("../API/php/footer.php");
?>