<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/kwynn');
require_once('kwutils.php');
require_once('regGet.php');
require_once('utils.php');
require_once('putPixels.php');

function getRadar() {
$geto = new regulated_get();
$mdat = $geto->rget();
foreach($mdat as $dat) {
    $path = $dat['tmp'];
    $img = base64_decode($dat['img']);
    file_put_contents($path, $img);
    putPixels($dat['rid'], $path, $dat['short_id'], $dat['seq'], $dat['mod'], $dat['modts']);
}
}
