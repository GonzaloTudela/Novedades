<?php
if (isset($_POST['id_noticia'])){
    $id=$_POST['id_noticia'];
    echo $id;
} else {
    echo 'error';
}