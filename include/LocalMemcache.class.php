<?php

class LocalMemcache{
private $_m;

public function init($host='127.0.0.1', $port='8221') {
    $this->_m = new Memcache;
    $this->_m->connect($host, $port);
    return;
}

public function set($key, $value, $duration=86400) {
    $ret = memcache_set($this->_m, $key, $value, 0, $duration);
    return $ret;
}

public function get($key) {
    $ret = memcache_get($this->_m, $key);
    return $ret;
}

};


?>
