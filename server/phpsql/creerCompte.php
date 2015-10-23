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
$params->arrayInput = array("input_nom","input_cle","input_code_user","input_mail","input_description","input_cv");
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
// phpsql/creerCompte.php?milis=123450&ctrl=ok&input_nom=Test&input_cle=cle&input_code_user=TEST&input_mail=test@mail.com&input_description=hello&input_cv=truc.pdf

//--------------------------------------------------------------------------
$params = new OdaPrepareReqSql();
$params->sql = "INSERT INTO `tab_utilisateurs`
    (`login`, `password`, `code_user`, `nom`, `profile`, `description`, `montrer_aide_ihm`, `mail`, `actif`, `date_creation`) 
    VALUES 
    (:nom,:cle,:code_user,:nom,99,:description,0,:mail,1,NOW())
;";
$params->bindsValue = [
    "nom" => $INTERFACE->inputs["input_nom"]
    , "cle" => $INTERFACE->inputs["input_cle"]
    , "code_user" => $INTERFACE->inputs["input_code_user"]
    , "description" => $INTERFACE->inputs["input_description"]
    , "mail" => $INTERFACE->inputs["input_mail"]
];
$params->typeSQL = OdaLibBd::SQL_INSERT_ONE;
$retour = $INTERFACE->BD_ENGINE->reqODASQL($params);

//--------------------------------------------------------------------------
$params = new stdClass();
$params->label = "resultatInsert";
$params->value = $retour->data;
$INTERFACE->addDataStr($params);