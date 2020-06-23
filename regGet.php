<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/kwynn');

require_once('/opt/kwynn/kwutils.php');
require_once('get.php');
require_once('dao.php');
require_once('isKwGoo.php');

class regulated_get extends radar {
    const tmpf = '/tmp/radar_temp.txt';
    const testAddEx = 0;
       
    function addedTime() {
	// if (php_uname('n') === '[...]') return self::testAddEx;
	return 0;
    }
    
    function __construct() {
	parent::__construct();
	$this->dao = new radar_dao();
    }
    
    public function rget() {
	
	foreach($this->lkeys as $rid) {
	
	$dbdates = $this->dao->getLatest(true, $rid);

	if (!isset($dbdates['exts'])) $donew = true;
	else $donew = time() >= $dbdates['exts'] + self::testAddEx  ? true : false;
	
	if ($donew) $donew = isKwDev() || isKwGoo() || PHP_SAPI === 'cli';
	
	if ($donew) {
	    $rado = new radar();
	    $livedates = $rado->get(true);
	    if (isset($dbdates['modts']) && $livedates['modts'] <= $dbdates['modts']) return $this->dao->getLatest(false, $this->lkeys);
	    $mdat = $rado->get(false);
	    foreach($mdat as $rid => $dat) {
		$dat['rid'] = $rid;
		$dat['seq'] = $this->dao->put($dat);
		$dat = array_merge($dat, $this->getURLs($rid));
		$dat['seq'] = $this->dao->put($dat);
		$rdat[$rid] = $dat;
	    }
	    return $rdat;
	} else return $this->dao->getLatest(false, $this->lkeys);
	}
	
    }
    public function getURLs($rid) {
	$res = $this->dao->getLatest(true, $rid, true);
	$fn = self::getFNfromModTS($res['modts'], $rid);
	$urlPast =        $this->locs[$rid]['pastUrlBase'] . $fn;
	return ['curr' => $this->locs[$rid]['urlCurr'], 'past' => $urlPast, 'tmp' => '/tmp/' . $fn];
    }
}