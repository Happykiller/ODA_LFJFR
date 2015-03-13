<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$retour_array = array('code' => null,
    'message' => null
);

$dossier = '../cloud/happytor/';
$fichier = basename($_FILES['file-0']['name']);
$taille_maxi = 1000000;
$taille = filesize($_FILES['file-0']['tmp_name']);
$extensions = array('.torrent');
$extension = strrchr($_FILES['file-0']['name'], '.'); 
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
    $retour_array['code'] = 'ko';
    $retour_array['message'] = 'Vous devez uploader un fichier de type torrent ...';
}
if($taille>$taille_maxi)
{
    $retour_array['code'] = 'ko';
    $retour_array['message'] = 'Le fichier est trop gros...'; 
}
if(!isset($retour_array['code'])) //S'il n'y a pas d'erreur, on upload
{
    

    //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     $path = $dossier . $fichier;
     if(move_uploaded_file($_FILES['file-0']['tmp_name'], $path)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
        $retour_array['code'] = 'ok';
        $details_array = array('message'=>'Upload effectue avec succes.','path'=>$path,'dossier'=>$dossier,'fichier'=>$fichier,'taille_maxi'=>$taille_maxi,'taille'=>$taille,'extensions'=>$extensions,'extension'=>$extension);
        $retour_array['message'] = $details_array; 
     }
     else //Sinon (la fonction renvoie FALSE).
     {
        $retour_array['code'] = 'ko';
        $details_array = array('message'=>'Echec de l upload.','path'=>$path,'dossier'=>$dossier,'fichier'=>$fichier,'taille_maxi'=>$taille_maxi,'taille'=>$taille,'extensions'=>$extensions,'extension'=>$extension);
        $retour_array['message'] = $details_array;
     }
}

$retour_json = json_encode($retour_array);
echo $retour_json;
?> 