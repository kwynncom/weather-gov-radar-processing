<?php

function getDBZxy($img, $x, $y, $c24todbzaIN = false) {
    
    static $c24todbza = false;
    
    if (!$c24todbza) { $c24todbza = $c24todbzaIN; return; }
    
    $rgb = imagecolorat($img, $x, $y);
    $c = imagecolorsforindex($img, $rgb);
    $r = $c['red'];
    $g = $c['green'];
    $b = $c['blue'];

    $c24 = ($r << 16) + ($g << 8) + $b;
    if (!  isset($c24todbza[$c24])) { $dbz = 0; }
    else $dbz =       $c24todbza[$c24];
    
    return $dbz;
    
}