<?php

function clutterFilter($img, $dat, $idbz, &$dofilt, $maxx = -1, $maxy = -1) {
    
    static $mx = 0;
    static $my = 0;
    
    if ($maxx >= 0) $mx = $maxx;
    if ($maxy >= 0) $my = $maxy;
    
    if ($dat === 0) return;

    // if (isset($dat['dbz']) && $dat['dbz'] !== 20 && time() < strtotime('2019-09-03 23:00') && php_uname('n') === [...]) return;
    
    $dbzds = [15 => 25, 20 => 15, 25 => 20, 30 => 35];
    
    $x = $dat['x'];
    $y = $dat['y'];
    
    if ($x === 292 && $y === 275) {
	$abcd = 2332;
    }
    
    $dbz = $idbz[$x][$y];
    
    if (!isset($dbzds[$dbz])) {
	return;
    }
    
    if ($dbzds[$dbz] > $dat['dist']) {
	return;
    }

    $r = 2;
        
    $c = 0;
    $gt = 0;
    for ($i=-$r; $i <= $r; $i++) 
    for ($j=-$r; $j <= $r; $j++) {
	
	$x = $dat['x'] + $i;
	$y = $dat['y'] + $j;
	
	if ($x < 0 || $x >= $mx || $y < 0 || $y >= $my) continue;

	$c++;
	if (isset($idbz[$x][$y]) && $idbz[$x][$y] >= $idbz[$dat['x']][$dat['y']]) $gt++;
    }
    
    if ($gt < $c) {
	$dofilt[$dat['x']][$dat['y']] = true;
    }
    else  {
	$dofilt[$dat['x']][$dat['y']] = false;
    }
}

function distFilter($dbz, $d) {
    if ($dbz <= 25 && $d > 30) return true;
}