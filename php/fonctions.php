<?php
function exemple($p_var) {
    try {
        $object_retour = new stdClass();
        $object_retour->strErreur = "";
        $object_retour->data = "";
        
        $object_retour->data = $p_var;

        return $object_retour;
    } catch (Exception $e) {
        $object_retour = new stdClass();
        $msg = $e->getMessage();
        $object_retour->strErreur = $msg;
        $object_retour->strData = "";
        return $object_retour;
    }
}
?>