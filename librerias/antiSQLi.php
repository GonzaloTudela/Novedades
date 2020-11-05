<?php
// Funcion casera para evitar inyecciones SQL.
function antiSQLi($string){
    $array=array("'","","\"","%","=","&","0x");
    return str_replace($array,"",$string);
}

?>