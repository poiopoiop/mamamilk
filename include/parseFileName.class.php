<?php 
    function parseFileName($fileName) {
        $pos = strrpos($fileName, '.');
        $file = substr($fileName, 0, $pos);
        return $file;
    }
?>
