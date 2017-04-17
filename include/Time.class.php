<?php
Class Time {
    public function getTimeStamp() {
        list($usec, $sec) = explode(" ", microtime());
        return array("usec" => $usec, "sec" => $sec);
    }

    public function deltaTime($ts_start, $ts_end) {
        $delta = $ts_end['sec']+$ts_end['usec']-$ts_start['sec']-$ts_start['usec'];
        return $delta;
    }
}
