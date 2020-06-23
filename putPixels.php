<?php

require_once('legend.php');
require_once('getStormDist.php');
require_once('clutter.php');
require_once('dbzxy.php');

function putPixels($rid, $path, $shortId, $seq, $modr, $modts) {

$dao = new radar_dao();

$pi = $dao->getStatus($seq);
if ($pi === 'pixels_inserted'
	/*&& (
	    php_uname('n') !== '[...]' 
	||  time() > strtotime('2019-09-01')
	|| PHP_SAPI !== 'cli' */
//	)
    
    ) return;

unset($pi);
$tome = [];

$info = getimagesize($path);
$img  = imagecreatefromgif($path);

$lego = new radar_colors();

$c24todbza = $lego->getc24todbza();

$idbz = [];

getDBZxy(0,0,0,$c24todbza);

$pass1 = [];
$pass1xy = [];

for ($x=0; $x < $info[0]; $x += 1)
for ($y=0; $y < $info[1]; $y += 1)
{

    $dbz = getDBZxy($img, $x, $y);
   
    if ($dbz < 15) continue;
    
    $dat2 = getStormDist($x, $y);
    
    if ($dbz <= 25) if (distFilter($dbz, $dat2['dist'])) continue;
    
    $idbz[$x][$y] = $dbz;
    
    $dat1 = ['x' => $x, 'y' => $y, 'dbz' => $dbz, 'seq' => $seq, 'short_id' => $shortId];
    
    $dat = array_merge($dat1, $dat2);
    
    $pass1[$dbz][] = $dat;
    $pass1xy[$x][$y] = $dat;
}

asort($pass1);

$tofilt = [];
clutterFilter(0,0,$idbz,$pass2, $info[0], $info[1]);

foreach($pass1 as $dbz => $i) {
    foreach($i as $dat) {
	clutterFilter($img, $dat, $idbz, $tofilt);    
    }
    
    foreach($tofilt as $x => $dat)
    foreach($dat as $y => $boo) {
	if ($boo) $idbz[$x][$y] = 10;    
    }
}

$tome = [];
for ($x=0; $x < $info[0]; $x += 1)
for ($y=0; $y < $info[1]; $y += 1) {
    if (!isset($pass1xy[$x][$y])) continue;
   $dbz = $idbz[$x][$y];
   if ($dbz <= 15) continue;
   $dat = $pass1xy[$x][$y];
   if (!isset($tome[$dbz]['dist']) || $tome[$dbz]['dist'] > $dat['dist']) {
       if ($dbz >= 15) {
	   $dkdkd = 2523;
       }
       $tome[$dbz] = $dat;
   }
}
asort($tome);
assignColorsToMe($tome, $lego);

$fdat['mod'] = $modr;
$fdat['modts'] = $modts;

$fdat['tome'] = $tome;
$fdat['seq']  = $seq;
$fdat['short'] = $shortId;
$fdat['rid'] = $rid;

$dao->setPixelsInserted($seq);
$dao->putToMe($fdat);
$y = 2323;
}

function assignColorsToMe(&$tome, $lego) {
    foreach($tome as $dbz => $dat) {
	$color = $lego->getColorHuman($dbz);
	$tome[$dbz]['color'] = $color;
    }
}