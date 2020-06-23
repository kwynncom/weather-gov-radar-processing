<?php
/*
require_once('/opt/kwynn/kwutils.php');

    function check($tock) {
	kwas(isset($tock[3]) && $tock && is_string($tock), 'curl / http header invalid on face'); // size of 3 is somewhat arbitrary
	preg_match('/^HTTP\/[\d\.]+ (\d+)/', $tock, $matches); kwas(isset($matches[1]), 'regex 1 fail'); kwas($matches[1] === '200', 'http error');	
    }
    
    check('HTTP/2 200');
    
    $xyzkw = 2;
 * *
 */