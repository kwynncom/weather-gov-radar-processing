<?php

function getOneTableHT($lego, $tomeall) {

$ht  = '';
$ht .= '<table style="font-family: monospace">' . "\n";

if (isset($tomeall[0])) {
    $cap = $tomeall[0]['rid'];
    $ht .= "<caption>$cap</caption>\n"; unset($cap);
}

// $ht .= getLHT();

$alldbz = [];
foreach($tomeall as $tomerow) {
    $tome  = $tomerow['tome'];   
    foreach($tome as $dbz => $ignore) $alldbz[$dbz] = true;
}

$calldbz = count($alldbz);
if ($calldbz) krsort($alldbz);

$rowi=0;
$alldat = [];
foreach($alldbz as $dbz => $ignore) {
    $dbzflat[] = $dbz;
    for($i=0; $i < count($tomeall); $i++) $alldat[$dbz][$i] = $tomeall[$i];
}

$calldat = count($alldat);

if ($calldat) krsort($alldat);

$lastcoli = count($tomeall) - 1;

if ($calldat === 0) $rowlim = 1;
else $rowlim = $calldat;

for($rowi=-1; $rowi < $rowlim; $rowi++) {

    $ht .= '<tr>';
    
    $dbz = false;
    if (isset($dbzflat[$rowi]))
    $dbz = $dbzflat[$rowi];
    $xyz = 2;

for($coli=0; $coli <= $lastcoli; $coli++) {

    $modts  = $tomeall[$coli]['modts'];    
   
    if ($rowi === -1 && $coli === 0) $ht .= '<th>dBZ</th><th></th>';

    $rgb = $lego->getCSSRGB($dbz);
    if ($coli === 0 && $rowi >= 0) 
	if ($dbz) $ht .= "<td>$dbz</td><td class='color' style='background-color: $rgb'>&nbsp;</td>";
	else	  $ht .= '<td colspan="2"></td>';
    if ($rowi === -1) $ht .= '<th colspan="2">' . date('g:iA', $modts) . '</th>';
    if ($rowi >= 0 && isset($alldat[$dbz][$coli]['tome'][$dbz])) { 
	$tome =    $alldat[$dbz][$coli]['tome'][$dbz];
	
	$d = $tome['dist'];
	$ht .= "<td class='dist' style='" . decorateDist($dbz, $d) . "'>$d</td>";
	$ht .= "<td>" . degToLab($tome['dir']) . "</td>";
    } else if ($rowi >=0) $ht .= '<td style="text-align: right">&Oslash;</td><td></td>';
    
    // if (count($tomeall) === 1) $ht .= '<td style="text-align: right">&Oslash;</td><td></td>';
    
    if ($coli === $lastcoli) $ht .= '</tr>';
 }
}

$ht .= '</table>' . "\n";
return $ht;
}