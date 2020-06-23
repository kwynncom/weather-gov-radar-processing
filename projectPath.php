<?php

require_once('dao.php');

if (time() < strtotime('2019-08-28 03:00')) projectPath();

function projectPath() {
    $dao  = new radar_dao(); 
    $tomeall = $dao->getToMe(3600, 2);
    
    
    
    $x = 2;
    
}

