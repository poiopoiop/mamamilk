<?php
/* translate str to real array
 * input: $str = 'array("a" => 123, "b" => 234)';
 * output: $array = array("a" => 123, "b" => 234);
 */

class Str2Array {
    public function translate($str) {
        $str_tmp = $str;
        do{
            $word = Str2Array::nextWord($str_tmp);
        }while(!(false === $word));
        return $array;
    }

    private function nextWord(&$str) {
        $word = "";
        $in_Quotes = false;
        do {
            $char = Str2Array::nextChar($str);
            switch($char) {
                case " ":
                    break;
                case "\t":
                    break;
                case "\r":
                    break;
                case "\n":
                    break;
            };
        }
        while(false !== $char);

        return false;
    }

    private function nextChar(&$str) {
        if (strlen($str)>0) {
            $char = $str[0];
            $str = substr($str, 1);
            return $char;
        }
        else {
            return false;
        }
    }
};
