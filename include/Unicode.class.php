<?php

class Unicode {

/**
 * $str 原始中文字符串
 * $encoding 原始字符串的编码，默认GBK
 * $prefix 编码后的前缀，默认"&#"
 * $postfix 编码后的后缀，默认";"
 */
function unicode_encode($str, $encoding = 'GBK', $prefix = '&#', $postfix = ';', $big_endian = true) {
    $str = iconv($encoding, 'UCS-2', $str);
    $arrstr = str_split($str, 2);
    $unistr = '';
    for($i = 0, $len = count($arrstr); $i < $len; $i++) {
        $hex = bin2hex($arrstr[$i]);
        $dec = hexdec($hex);

        if ($big_endian) {
            //大端模式，高字节=>低地址
            $dec = ($dec%256)*256 + intval($dec/256);
        }

        $unistr .= $prefix . $dec . $postfix;
    } 
    return $unistr;
} 

 
/**
 * $str Unicode编码后的字符串
 * $decoding 原始字符串的编码，默认GBK
 * $prefix 编码字符串的前缀，默认"&#"
 * $postfix 编码字符串的后缀，默认";"
 */
function unicode_decode($unistr, $encoding = 'GBK', $prefix = '&#', $postfix = ';', $big_endian = true) {
    $arruni = explode($prefix, $unistr);
    $unistr = '';
    for($i = 1, $len = count($arruni); $i < $len; $i++) {
        if (strlen($postfix) > 0) {
            $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
        } 
        $temp = intval($arruni[$i]);
        
        if ($big_endian) {
            //大端模式，高字节=>低地址
            $unistr .= ($temp < 256) ? chr($temp) . chr(0) : chr($temp % 256) . chr($temp / 256);
        }
        else {
            //小端模式，高字节=>高地址
            $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
        }
    } 
    return iconv('UCS-2', $encoding, $unistr);
}


 
/**
 * $str Unicode编码后的字符串,!!!但其中英文字符没有进行编码!!!
 * $decoding 原始字符串的编码，默认GBK
 * $prefix 编码字符串的前缀，默认"&#"
 * $postfix 编码字符串的后缀，默认";"
 */
function unicode_decode_witheng($unistr, $encoding = 'GBK', $prefix = '&#', $postfix = ';', $big_endian = true) {
    $strlen = strlen($unistr);
    $substr = $unistr;
    $result = "";
    while ($strlen > 0) {
        if (preg_match('/^(&#\d+;)/', $substr, $output)) {
            $part = $output[1];
            $partLen = strlen($part);
            $strlen -= $partLen;

            $substr = substr($substr, $partLen);
            $result = $result.self::unicode_decode($part);;

        }
        elseif (preg_match('/^(&gt;)/', $substr, $output)) {
            $part = $output[1];
            $partLen = strlen($part);
            $strlen -= $partLen;

            $substr = substr($substr, $partLen);
            $result = $result.'>';
        }
        elseif (preg_match('/^(&lt;)/', $substr, $output)) {
            $part = $output[1];
            $partLen = strlen($part);
            $strlen -= $partLen;

            $substr = substr($substr, $partLen);
            $result = $result.'<';
        }
        elseif (preg_match('/^(&amp;)/', $substr, $output)) {
            $part = $output[1];
            $partLen = strlen($part);
            $strlen -= $partLen;

            $substr = substr($substr, $partLen);
            $result = $result.'&';
        }
        elseif (preg_match('/^(&mdash;)/', $substr, $output)) {
            $part = $output[1];
            $partLen = strlen($part);
            $strlen -= $partLen;

            $substr = substr($substr, $partLen);
            $result = $result.'-';
        }
        else {
            $part = $substr[0];
            $strlen --;
            $substr = substr($substr, 1);
            $result .= $part;
        }

    }

    return iconv('gbk', 'utf-8', $result);
}



};
