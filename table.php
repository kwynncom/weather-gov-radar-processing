<?php

require_once('/opt/kwynn/kwutils.php');
require_once('dao.php');
require_once('getAndDo.php');
require_once('radLightRow.php');
require_once('table1.php');

getRadar();

$dao  = new radar_dao(); 
$lego = new radar_colors();

$ht = '';

$tomeall = $dao->getToMe(); unset($dao);
foreach($tomeall as $rid => $row) $ht .= getOneTableHT($lego, $row);

unset($lego);

$ht .= '<p><a href="https://www.lightningmaps.org/?lang=en#m=oss;t=3;s=0;o=0;b=0.00;ts=0;z=10;y=34.2464;x=-84.0413;d=2;dl=2;dc=0;">';
$ht .= 'lightning map</a></p>';


$ht .= '<p>disk use: ' . round(diskUse(), 1) . '%</p>' . "\n";




function decorateDist($db, $di) {
   if ($di >= 100) return 'color: gray';
   if ($db > 15 && $di < 30) return 'font-size: 120%; font-weight: bold';
   return '';
}

function diskUse() {
    $r = disk_free_space('/') / disk_total_space('/');
    $p = 100 - $r * 100;
    return $p;
}