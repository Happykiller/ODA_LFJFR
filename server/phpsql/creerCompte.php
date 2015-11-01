<?php
namespace Lfjfr;

require '../header.php';
require '../vendor/autoload.php';
require '../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$params->arrayInput = array("input_nom","input_cle","input_code_user","input_mail","input_description","input_cv");
$INTERFACE = new LfjfrInterface($params);

//--------------------------------------------------------------------------
// phpsql/creerCompte.php?milis=123450&ctrl=ok&input_nom=Test&input_cle=cle&input_code_user=TEST&input_mail=test@mail.com&input_description=hello&input_cv=truc.pdf

//--------------------------------------------------------------------------
$params = new OdaPrepareReqSql();
$params->sql = "INSERT INTO `api_tab_utilisateurs`
    (`password`, `code_user`, `nom`, `id_rang`, `description`, `montrer_aide_ihm`, `mail`, `actif`, `date_creation`)
    VALUES 
    (:cle,:code_user,:nom,1,:description,0,:mail,1,NOW())
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

$config = \Oda\SimpleObject\OdaConfig::getInstance();
if(isset($config->resourcesPath)){
    if (!file_exists("../" . $config->resourcesPath . $INTERFACE->inputs["input_code_user"])) {
        mkdir("../" . $config->resourcesPath . $INTERFACE->inputs["input_code_user"], 0777, true);
    }
}else{
    if (!file_exists("../".$INTERFACE->inputs["input_code_user"])) {
        mkdir("../".$INTERFACE->inputs["input_code_user"], 0777, true);
    }
}

//--------------------------------------------------------------------------
$params = new stdClass();
$params->label = "resultatInsert";
$params->value = $retour->data;
$INTERFACE->addDataStr($params);