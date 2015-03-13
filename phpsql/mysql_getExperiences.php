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
// phpsql/mysql_getExperiences.php?milis=123456789&critere=java,sql

// [0][0] =  ordre
// [0][1] =  titre_experience
// [0][2] =  commentaire_titre
// [0][3] =  debut
// [0][4] =  fin
// [0][5] =  employeur
// [0][6] =  fonction
// [0][7] =  secteur
// [0][8] =  fonctionnel
// [0][9] =  realisation
// [0][10] = language
// [0][11] = applicatif
// [0][12] = develloppement
// [0][13] = bdd

//IN obligatoire
$arrayInput = array(
    "critere" => null
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

$critere = strtolower ($arrayValeur["critere"]);

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
$req = $connection->prepare($strSql);

if($req->execute()){
    $req->setFetchMode(PDO::FETCH_OBJ);
    $resultats = new stdClass();
    $resultats->data = $req->fetchAll(PDO::FETCH_OBJ);
    $resultats->nombre = count($resultats->data);
    $object_retour->data["resultat"] = $resultats;
}else{
    $error = 'Erreur SQL:'.print_r($req->errorInfo(), true)." (".$strSql.")";
    $object_retour->strErreur = $error;
}
$req->closeCursor();

//--------------------------------------------------------------------------
if($modeDebug){
    print_r($strSql);
}

//---------------------------------------------------------------------------

//Cloture de l'interface
require("../API/php/footer.php");
?>