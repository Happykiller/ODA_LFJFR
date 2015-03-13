<?php
//Config : Les informations personnels de l'instance (log, pass, etc)
require_once(dirname(__FILE__)."/../include/config.php");

//API lib for ODA
require_once(dirname(__FILE__).'/../API/php/liboda.class.php');

//Old API lib for ODA
require_once(dirname(__FILE__)."/../API/php/fonctions.php");

//Project functions
require_once(dirname(__FILE__)."/../php/fonctions.php");

//API lib for ODA
$liboda = new LIBODA();

//--------------------------------------------------------------------------
// On transforme les résultats en tableaux d'objet
$retours = array();

//--------------------------------------------------------------------------
$retours[] = test("get_string_between",function() {
        $v_test = get_string_between("01234", "1", "3");
        equal($v_test, "2", "Test OK : Passed!");
    }         
);

//--------------------------------------------------------------------------
//Out
$resultats = new stdClass();
$resultats->details = $retours;
$resultats->succes = 0;
$resultats->echec = 0;
$resultats->total = 0;
foreach($retours as $key => $value) {
    $resultats->succes += $value->succes;
    $resultats->echec += $value->echec;
    $resultats->total += $value->total;
 }

//--------------------------------------------------------------------------
$resultats_json = json_encode($resultats);

$strSorti = $resultats_json;

print_r($strSorti);
?>