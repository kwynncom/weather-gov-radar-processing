<?php

function getStormDist($rx, $ry) {

    
    static $mx = 351; // BACK TO STATIC AND THEN DO NOT UNSET BELOW
    static $my = 175;
    
    $dx = $rx - $mx;
    $dy = $my - $ry;
    
    $dist = intval(round(sqrt($dx * $dx + $dy * $dy) * 0.4879));  // 0.4879 miles / pixel
    $deg = intval(round(compass($dx, $dy)));
  
    $ret = ['mx' => $mx, 'my' => $my, 'dist' => $dist, 'dir' => $deg];
    unset(/* $mx, $my, */ $dx, $dy);
    
    return $ret;
}

function compass($x,$y)
    {
        if($x==0 AND $y==0){ return 360; } // ...or return 360
        return ($x < 0)
        ? rad2deg(atan2($x,$y))+360      // TRANSPOSED !! y,x params
        : rad2deg(atan2($x,$y)); 
    }