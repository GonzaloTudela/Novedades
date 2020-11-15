<?php
function escribeNovedades()
{
    foreach ($novedades as $keyNovedades) {
        $escritura = substr($keyNovedades['timestamp_not'], 0, -3);
        echo '<div class="card altura2 sombra2">';
//            echo '<p class="card txt3">Autor:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['id_usuario'].'</p>';
//            echo '<p class="card txt2">Titulo:</p>';
        echo '<p class="titulo txt2">' . $keyNovedades['titulo'] . '</p>';
//            echo '<p class="card txt3">Cuerpo:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['cuerpo'].'</p>';
//            echo '<p class="card txt3">Fecha Inicio:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['fecha_inicio'].'</p>';
//            echo '<p class="card txt3">Fecha Fin:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['fecha_fin'].'</p>';
//            echo '<p class="card txt3">Escrita el:</p>';
        echo '<p class="minifecha txt3">' . $escritura . '</p>';
//            echo '<p class="txt1">Version:</p><br>';
//            printf ($keyNovedades['num_version']);
        echo '</div>';
    }
}

/*
    MODIFIED VERSION FOR LEARNING PURPOSES BY Gonzalo Tudela 2020.
    --------------------------------------------------------------------------------------------------------------------
    Paul's Simple Diff Algorithm v 0.1
    (C) Paul Butler 2007 <http://www.paulbutler.org/>
    May be used and distributed under the zlib/libpng license.

    This code is intended for learning purposes; it was written with short
    code taking priority over performance. It could be used in a practical
    application, but there are a few ways it could be optimized.

    Given two arrays, the function diff will return an array of the changes.
    I won't describe the format of the array, but it will be obvious
    if you use print_r() on the result of a diff on some test data.

    htmlDiff is a wrapper for the diff command, it takes two strings and
    returns the differences in HTML. The tags used are <ins> and <del>,
    which can easily be styled with CSS.
*/
function diff($old, $new)
{
    $matrix = array();
    $maxlen = 0;
    foreach ($old as $oindex => $ovalue) {
        $nkeys = array_keys($new, $ovalue);
        foreach ($nkeys as $nindex) {
            $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
            if ($matrix[$oindex][$nindex] > $maxlen) {
                $maxlen = $matrix[$oindex][$nindex];
                $omax = $oindex + 1 - $maxlen;
                $nmax = $nindex + 1 - $maxlen;
            }
        }
    }
    if ($maxlen == 0) return array(array('d' => $old, 'i' => $new));
    return array_merge(
        diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
        array_slice($new, $nmax, $maxlen),
        diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

function htmlDiff($old, $new)
{
    $ret = '';
    $diff = diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
    foreach ($diff as $k) {
        if (is_array($k))
            // ORIGINAL
//            $ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
//                (!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
            // SOLO CAMBIOS
            $ret .= (!empty($k['i']) ? "<ins>" . implode(' ', $k['i']) . "</ins> " : '');
        else $ret .= $k . ' ';
    }
    return $ret;
}