<?php

require_once("/home/iknow/workroot/include/Curl.class.php");
require_once("/home/iknow/workroot/include/NLog.class.php");
require_once("/home/iknow/workroot/include/FileRead.class.php");
require_once("/home/iknow/workroot/include/LocalMemcache.class.php");
require_once("/home/iknow/workroot/include/Id.class.php");
require_once("/home/iknow/workroot/include/Time.class.php");

$ts = Time::getTimeStamp();
$qr = new QueryResult();
$qr->run();
$te = Time::getTimeStamp();
$tDelta = Time::deltaTime($ts, $te);
echo "timecost: $tDelta\n";

class QueryResult {
    private $_logger;
    private $_fr;
    private $_curl;
    private $_mc;

    public function __construct() {
        $this->_logger = new NLog();
        $this->_logger->init("./log/ana.log");
        $this->_fr = new FileRead();
        $this->_fr->init("./di/di");
        $this->_curl = new Curl();
        $this->_mc = new LocalMemcache();
        $this->_mc->init();
        return;
    }

    public function run() {
        $this->_traversal();
        return;
    }

    private function _traversal() {
        while ($line = $this->_fr->readLine()) {
            var_dump($line);
            sleep(1);
        }
        return;
    }


};
