<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
ini_set('display_errors',1);
error_reporting(E_ALL);

// php/delete.php?happytorfile=Avengers.Assemble.S01E01.FASTSUB.VOSTFR.720p.HDTV.x264-PROTEiGON.torrent

$retour_array = array('code' => null,
    'message' => null
);

$dossier = '../cloud/happytor/';

if(isset($_GET['happytorfile']))
{
    $fichier = $_GET['happytorfile'];
    $path = $dossier . $fichier;
    if(unlink($path)) 
     {
        $retour_array['code'] = 'ok';
        $details_array = array('message'=>'Fichier supprime avec succes.','path'=>$path);
        $retour_array['message'] = $details_array; 
     }
     else //Sinon (la fonction renvoie FALSE).
     {
        $retour_array['code'] = 'ko';
        $details_array = array('message'=>'Echec de la suppression.','path'=>$path);
        $retour_array['message'] = $details_array;
     }
}else{
    $retour_array['code'] = 'ko';
    $retour_array['message'] = 'Pas torrent passe en parametre ...';
}
   
$retour_json = json_encode($retour_array);
echo $retour_json;

?> 