<?php

function getRadLightRow($radall) { // 2019/08/27 12:21AM 00:21 - I think this works, now have to add dir, dist, type as assoc.
    
    return; // didn't need any of this; 2019/09/02 11:48pm
    
    $dao    = new lightning_dao();
    $recall = $dao->getClosest(); unset($dao);
    
    $aa = [];
    foreach($radall as $i => $radr) $aa[$radr['modts']] = 'rad';
    foreach($recall as $i => $recr) {
	if ($recr['closest'] === false) $aa[$recr['atts']]         = 'rec';
	else				$aa[$recr['closest']['U']] = 'rec';
    }
    
    krsort($aa);
    
    $reta = [];
    $i = 0;
    foreach($aa as $ts => $type) {
	if ($type === 'rec' && !isset($reta[$i])) $reta[$i] = $ts;
	else $i++;
    }
    $x = 2;
}


function getRadLightRowAttempt1($radall) {
    $dao    = new lightning_dao();
    $recall = $dao->getClosest(); unset($dao);
    
    foreach($radall as $i => $radr) $rad[$i] = $radr['modts'];
    foreach($recall as $i => $recr) {
	if ($recr['closest'] === false) $rec[$i] = $recr['atts'];
	else				$rec[$i] = $recr['closest']['U'];
    }
    
    $recall[] = 0;
        
    $radl = count($radall) - 1;
    $recl = count($recall) - 1;
    
    for ($radi=0; $radi <= $radl; $radi++)
    for ($reci=0; $reci <= $recl; $reci++)
    {
	if ($rec) ;
    }
    
    $x = 2;
}