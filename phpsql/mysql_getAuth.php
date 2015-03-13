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
// phpsql/mysql_getAuth.php?milis=123456789&cle=fuel

// IN obligatoire
$arrayInput = array(
    "cle" => null
);

//Récupération des entrants
$arrayValeur = recupInput($arrayInput);

//Object retour minima
// $object_retour->strErreur string
// $object_retour->data  string
// $object_retour->statut  string

//--------------------------------------------------------------------------
$sql = "select a.`profile`, a.`code_user` 
    from `".$prefixTable."tab_utilisateurs` a
    where 1=1 
    and a.`password` = '".$arrayValeur["cle"]."'
";

// On envois la requète
$req = $connection->query($sql) or die('Erreur SQL !'.$sql.'<br>'.print_r($connection->errorInfo(), true));

$rows = array();
if($req->execute()){
    $rows = $req->fetch();
    $object_retour->data["resultat"] = $rows;
    if($object_retour->data["resultat"] != false){
        $params = array();
        $params["code_user"] = $object_retour->data["resultat"]["code_user"];
        $key = buildSession($params, $connectionAuth, $prefixTable);
        $object_retour->data["resultat"]["keyAuthODA"] = $key;
    }
}else{
    die('Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")");
}
$req->closeCursor();

//--------------------------------------------------------------------------
if($modeDebug){
    $strSorti .= ('<br><br><br><br>'.$sql);
}

//Cloture de l'interface
require("../API/php/footer.php");
?>