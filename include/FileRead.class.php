<?php

class FileRead {
    private $_fp;
    
    public function init($file) {
        $this->_fp = fopen($file, "r");
        if ($this->_fp == false || $this->_fp == NULL) {
            return false;
        }
        return true;
    }

    public function readLine() {
        $buf = NULL;
        if (!feof($this->_fp)) {
            $buf = fgets($this->_fp);
        }
        return $buf;
    }
}
