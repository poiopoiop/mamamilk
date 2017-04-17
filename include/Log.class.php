<?php

require_once("/home/iknow/workroot/conv_bcs/include/Curl.class.php");

class Log {
    private $_log_file;
    private $_netlog_flag;
    private $_netlog_curl;

    public function init($log_file) {
        $this->_log_file = $log_file;
        $this->_netlog_flag = 0;
        return;
    }

    public function initNetLog($host, $port, $connect_timeout_ms=1000, $timeout_ms=1000) {
        $this->_netlog_flag = 1;
        $this->_netlog_curl = new Curl();
        $this->_netlog_curl->init($host, $port, $connect_timeout_ms, $timeout_ms);
        return;
    }

    public function addLog($level, $str) {
        $line = $level." ".date("Ymd H:i:s")." ".$str."\n";
        $this->_addLocalLog($line);
        return;
    }

    public function addNetLog($level, $str) {
        $line = "level=$level&log=".urlencode($str);
        if ($this->_netlog_flag == 1) {
            $this->_addNetLog($line);
        }
        return;
    }

    private function _addLocalLog($line) {
        file_put_contents($this->_log_file, $line, FILE_APPEND);
        return;
    }

    private function _addNetLog($line) {
        $this->_netlog_curl->call("/netlog/", $line);
        return;
    }
}

?>
