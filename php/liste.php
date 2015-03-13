<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
ini_set('display_errors',1);
error_reporting(E_ALL);

// php/liste.php

$retour_array = array('code' => null,
    'message' => null
);

$path = '../cloud/happytor/';
$liste_array = array();

if($dossier = opendir($path))
{
    while(false !== ($fichier = readdir($dossier)))
    { 
        if($fichier != '.' && $fichier != '..' && $fichier != 'index.php')
        {
            $liste_array[] = $fichier;
        }
    }
}

$retour_array['code'] = 'ok';
$retour_array['message'] = $liste_array; 
   
$retour_json = json_encode($retour_array);
echo $retour_json;

?> 