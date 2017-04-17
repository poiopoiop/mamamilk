<?php

class NCurl {
private $_ch;
private $_host;
private $_port;

public function init($host, $port, $connect_timeout_ms=1000, $timeout_ms=1000) {
    $this->_host = $host;
    $this->_port = $port;
    $this->_ch = curl_init();
    curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT_MS, $connect_timeout_ms);
    curl_setopt($this->_ch, CURLOPT_TIMEOUT_MS, $timeout_ms);
}

public function call($url, $param) {
    $ret = $this->_curl_call($this->_host.":".$this->_port.$url."?".$param);
    return $ret;
}

private function _curl_call($url) {
    /* set url option */
    curl_setopt($this->_ch, CURLOPT_URL, $url);

    /* rederect the output to file */
    //curl_setopt($this->_ch, CURLOPT_FILE, $fp);

    /* redirect the output to return var */
    //curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 0);

    /* return page content without header */
    //curl_setopt($this->_ch, CURLOPT_HEADER, 0);

    /* callback function processing header */
    //curl_setopt($this->_ch, CURLOPT_HEADERFUNCTION, 'read_header');

    /* enable POST mode */
    curl_setopt($this->_ch, CURLOPT_POST, 0);

    /* set post field */
    //curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $body);

    $ret = curl_exec($this->_ch);
    return $ret;
}

}

class NLog {
    private $_log_file;
    private $_netlog_flag;
    private $_netlog_curl;
    private $_pid;

    public function init($log_file) {
        $this->_pid = getmypid();
        $this->_log_file = $log_file;
        $this->_netlog_flag = 0;
        return;
    }

    public function initNetLog($host, $port, $connect_timeout_ms=1000, $timeout_ms=1000) {
        $this->_netlog_flag = 1;
        $this->_netlog_curl = new NCurl();
        $this->_netlog_curl->init($host, $port, $connect_timeout_ms, $timeout_ms);
        return;
    }

    public function addLog($level, $str) {
        $line = $level." ".date("Ymd H:i:s")." ".$this->_pid." ".$str."\n";
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
