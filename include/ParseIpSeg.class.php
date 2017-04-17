<?php

class ParseIpSeg {
    public function __construct() {
        return;
    }

    public function parse($input) {
        preg_match('/(\d+)\.(\d+)\.(\d+)\.(\d+)-(\d+)\.(\d+)\.(\d+)\.(\d+)/', $input, $output);

        //暂时只支持后两段的遍历
        if ($output[1] != $output[5] 
            || $output[2] != $output[6]) {
            return false;
        }

        $segs = array();
        for ($i=$output[3]; $i<=$output[7]; $i++) {
            $segs[] = $output[1].'.'.$output[2].'.'.$i;
        }
        return $segs;
    }
};
