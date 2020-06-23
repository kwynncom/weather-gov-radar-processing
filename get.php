<?php

require_once('config.php');

class radar {
    const urlBase1 = 'https://radar.weather.gov/RadarImg/';
    const urlBase2    = self::urlBase1 . self::urlDir. '/';
    const urlDir = 'NCR';
   
    protected $locs;
    protected $lkeys;
    
    public function __construct() {
	$in = getRadarConfig();
	foreach($in as $rad) {
	    $rid = $rad['rid'];
	    $rad['urlCurr']     = self::urlBase2 . $rid . '_' . self::urlDir . '_0.gif';
	    $rad['pastUrlBase'] = self::urlBase2 . $rid . '/';
	    $c[$rid] = $rad;
	    $this->lkeys[] = $rid;
	}
		
	$this->locs = $c;
    }
    
    protected function get($honly = false) {
	
	foreach ($this->locs as $rid => $rad) {
	    $head = self::getInternal(true, $rid);

	    $dateTypes = ['Last-Modified: ' => 'mod', 'Expires: ' => 'ex', 'Date: ' => 'gotat'];

	    foreach($dateTypes as $type => $short) {
		
		$headcmp = strtolower($head);
		$typecmp = strtolower($type);
		preg_match('/' . $typecmp  . '([^\n]+)/', $headcmp, $matches); kwas(isset($matches[1]), 'date regex fail');
		$ts = intval(strtotimeRecent($matches[1]));
		$dates[$short . '']   = $matches[1];
		$dates[$short . 'ts'] = $ts;
	    }

	    $dates['file']  = self::getFNfromModTS($dates['modts'], $rid);
	    $dates['url']   = $this->locs[$rid]['pastUrlBase'] . $dates['file'];

	    if ($honly) return $dates;

	    $ret = $dates;

	    $img = self::getInternal(false, $rid);
	    $ret['size'] = strlen($img);
	    $ret['img'] = base64_encode($img);
	    $ret['rid'] = $rid;
	    $ret['short_id'] = dateTZ('Hi', $ret['modts'], 'America/New_York');
	    $mret[$rid] = $ret;
	}
	
	return $mret;
	
    }
    
    protected static function getFNfromModTS($ts, $rid) {
	$ts = strtotimeRecent($ts, true);
	$dates = dateTZ('Ymd_Hi', $ts, 'GMT');
	$fname = $rid . '_' . $dates . '_' . radar::urlDir . '.gif';        
	return $fname;
    }
    
    
    private function getInternal($honly, $rid) {

	$curl = curl_init();
	
	if ($honly) {
	    curl_setopt($curl, CURLOPT_NOBODY, true);
	    curl_setopt($curl, CURLOPT_HEADER, true);
	}
	
	$url = $this->locs[$rid]['urlCurr'];

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_FILETIME, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_USERAGENT, kwua());
	$got = curl_exec($curl);  
	if ($honly) self::check($got);
	curl_close($curl);
	
	return $got;
    }
    
    private static function check($tock) {
	kwas(isset($tock[3]) && $tock && is_string($tock), 'curl / http header invalid on face'); // size of 3 is somewhat arbitrary
	preg_match('/^HTTP\/[\d\.]+ (\d+)/', $tock, $matches); kwas(isset($matches[1]), 'regex 1 fail'); kwas($matches[1] === '200', 'http error');	
    }
}