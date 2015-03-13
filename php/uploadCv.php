<?php
//Config : Les informations personnels de l'instance (log, pass, etc)
require("../include/config.php");

//API Fonctions : les fonctions fournis de base par l'API
require("../API/php/fonctions.php");

//Header établie la connection à la base $connection
require("../API/php/header.php");

//Fonctions : Fonctions personnelles de l'instance
require("../php/fonctions.php");

//Définition des entrants
$arrayGet = array(
    "dossier" => null,
    "nom" => null
);

//Récupération des entrants
$arrayValeur = recupInput($arrayGet, $bolDecode);

if($arrayValeur["error"] == null){

    //init retour
    $retour_array = array(
        'code' => null,
        'message' => null,
        'id_pj' => null
    );

    //Init traitement
    $dossier = '../'.$arrayValeur["dossier"];
    $fichier = basename($_FILES['file-0']['name']);
    //On formate le nom du fichier ici...
    $fichier_format = strtr($fichier, 
         'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ$&#%!§', 
         'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy------');
    $fichier_format = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier_format);
    $fichier_format = addslashes($fichier_format);
    $taille_maxi = 500000;
    $taille = filesize($_FILES['file-0']['tmp_name']);
    $extensions = array('.jpg','.pnj','.txt','.doc','.docx','.xls','.xlsx','.msg','.pdf');
    $extension = strrchr($_FILES['file-0']['name'], '.'); 
    $extension = strtolower($extension);
    
    //Vérification extension
    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        $retour_array['code'] = 'ko';
        $retour_array['message'] = 'Mauvais format de fichier (.jpg,.pnj,.txt,.doc,.docx,.xls,.xlsx,.msg,.pdf).';
    }
    
    //Vérification taille
    if($taille>$taille_maxi)
    {
        $retour_array['code'] = 'ko';
        $retour_array['message'] = 'Le fichier est trop gros 500ko max.'; 
    }
    
    //Si tj ok on trait(e
    if(!isset($retour_array['code'])) //S'il n'y a pas d'erreur, on upload
    {
        //Upload le fichier
        if($arrayValeur["nom"] != ""){
            $path = $dossier . $arrayValeur["nom"];
        }else{
            $path = $dossier . $fichier_format;
        }
        if(move_uploaded_file($_FILES['file-0']['tmp_name'], $path)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
           $retour_array['code'] = 'ok';
           $details_array = array('message'=>'Upload effectue avec succes.','path'=>$path,'dossier'=>$dossier,'fichier'=>$fichier_format,'taille_maxi'=>$taille_maxi,'taille'=>$taille,'extensions'=>$extensions,'extension'=>$extension);
           $retour_array['message'] = $details_array; 
        } else {
            $retour_array['code'] = 'ko';
            $details_array = array('message'=>'Echec de l upload.','path'=>$path,'dossier'=>$dossier,'fichier'=>$fichier_format,'taille_maxi'=>$taille_maxi,'taille'=>$taille,'extensions'=>$extensions,'extension'=>$extension);
            $retour_array['message'] = $details_array;
        }
    }

    $resultats_json = json_encode($retour_array);
    
    $strSorti = $resultats_json;

}else{
    //Problème sur les entrants
    $strSorti = "ERROR:".$arrayValeur["error"];
}

//Cloture de l'interface
require("../API/php/footer.php");
?>