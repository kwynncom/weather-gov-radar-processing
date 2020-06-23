<?php

require_once('/opt/kwynn/kwutils.php');

class radar_dao extends dao_generic {
    const db = 'radar';
    function __construct() {
	parent::__construct(self::db);
	$this->icoll    = $this->client->selectCollection(self::db, 'img');
	$this->pxcoll   = $this->client->selectCollection(self::db, 'pixels');
	$this->tomecoll = $this->client->selectCollection(self::db, 'tome');
    }
    
    public function put($dat) {
	
        $seq = $this->getSeq('radar_img');
        $dat['seq'] = $seq;
        $this->icoll->upsert(['rid' => $dat['rid'], 'modts' => $dat['modts']], $dat);

	return $seq;
    }
    
    public function putToMe($dat) { $this->tomecoll->upsert(['seq' => $dat['seq']], $dat); }
    
    public function getToMe($since = 12000, $limit = 8) {
	$rbresa = $this->tomecoll->find(['modts' => ['$gt' => time() - $since]], ['sort' => ['modts' => -1], 'limit' => $limit])->toArray();

	$bresa = [];
	foreach($rbresa as $row) {
	    // $breasa[$row['rid']] = $row;
	    krsort($row['tome']);
	    if (!isset($row['rid'])) continue;
	    $bresa[$row['rid']][] = $row;
	}


	return $bresa;
    }
    
    public function putPixel($dat) {
	$this->pxcoll->upsert(['x' => $dat['x'], 'y' => $dat['y'], 'seq' => $dat['seq']], $dat);
    }

    private function getLatestAll($rida) {
	foreach($rida as $rid) {
	    $res = $this->icoll->findOne(['rid' => $rid], ['sort' => ['exts' => -1]]);	    
	    $ret[$res['rid']] = $res;
	}
	
	return $ret;
	
    }
    
    public function getLatest($tsonly = false, $rid, $allts = false) {
	if (is_array($rid)) return $this->getLatestAll($rid);
	$res = $this->icoll->findOne(['rid' => $rid], ['sort' => ['exts' => -1]]);
	if      ($tsonly && !$allts) return $res['exts'];
	if ($tsonly &&  $allts) {
	    unset($res['img']);
	    return $res;
	}
	return $res;
    }
    
    public function setPixelsInserted($seq) {
	$this->icoll->upsert(['seq' => $seq], ['status' => 'pixels_inserted']);
    }
    
    public function getStatus($seq) {
	$res = $this->icoll->findOne(['seq' => $seq], ['projection' => ['status' => true]]);
	if (!isset($res['status'])) return false;
	return $res['status'];
    }
}
