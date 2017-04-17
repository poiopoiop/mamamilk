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

class ConfReader {
    private $_fr;

    public function __construct() {
        $this->_fr = new FileRead();
    }

    public function readConf($conf_file) {
        $this->_fr->init($conf_file);
        return $this->_parseConf();
    }

    private function _parseConf() {
        $pt_comment = "/^#/";
        $pt_blank = "/^$/";
        $pt_array = "/^@/";
        $pt_key = "/^\[/";

        $conf = array();

        $cur_keys = array();
        $cur_level = 0;
        $cur_conf = &$conf;

        while ($line = $this->_fr->readLine()) {
            $line = trim($line);
            if (preg_match($pt_comment, $line)) {
                continue;
            }
            if (preg_match($pt_blank, $line)) {
                continue;
            }
            if (preg_match($pt_key, $line)) {
                $info = $this->_parseKey($line);

                $cur_conf = &$conf;
                for ($i=1; $i<$info["level"]; $i++) {
                    $cur_conf = &$cur_conf[$cur_keys[$i]];
                }

                $cur_level = $info["level"];
                $cur_keys[$info["level"]] = $info["key"];
                $cur_conf[$info["key"]] = array();
                $cur_conf = &$cur_conf[$info["key"]];

                continue;
            }
            if (preg_match($pt_array, $line)) {
                $info = $this->_parseArray($line);
                $cur_conf[$info["key"]][] = $info["value"];
                continue;
            }

        }
        return $conf;
    }

    private function _parseArray($line) {
        $pattern = "/^@(\S+)([ ]*)?:([ ]*)?(\S+)/";
        preg_match($pattern, $line, $output);
        $info = array(
            "key" => $output[1],
            "value" => $output[4],
        );
        return $info;
    }

    private function _parseKey($line) {
        $pattern = "/^\[([\.]*)?([^\]]*)\]/";
        preg_match($pattern, $line, $output);
        $info = array(
            "level" => sizeof($output[1]),
            "key" => $output[2],
        );

        return $info;
    }
}

?>
