<?php

class Curl {
private $_ch;
private $_host;
private $_port;

public function init($connect_timeout_ms=1000, $timeout_ms=1000) {
    $this->_ch = curl_init();
    curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT_MS, $connect_timeout_ms);
    curl_setopt($this->_ch, CURLOPT_TIMEOUT_MS, $timeout_ms);
}

public function post($url, $post, $header=null) {
    $ret = $this->_post_call($url, $post, $header);
    return $ret;
}

private function _post_call($url, $post, $header) {
    /* set url option */
    curl_setopt($this->_ch, CURLOPT_URL, $url);

    /* rederect the output to file */
    //curl_setopt($this->_ch, CURLOPT_FILE, $fp);

    /* redirect the output to return var */
    curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);

    /* return page content without header */
    //curl_setopt($this->_ch, CURLOPT_HEADER, 0);

    /* callback function processing header */
    if (null != $header) {
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $header);
    }

    /* enable POST mode */
    curl_setopt($this->_ch, CURLOPT_POST, 1);

    /* set post field */
    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $post);

    $ret = curl_exec($this->_ch);
    return $ret;

}

public function call($url, $header=null) {
    $ret = $this->_curl_call($url, $header);
    return $ret;
}

private function _curl_call($url, $header) {
    /* set url option */
    curl_setopt($this->_ch, CURLOPT_URL, $url);

    /* rederect the output to file */
    //curl_setopt($this->_ch, CURLOPT_FILE, $fp);

    /* redirect the output to return var */
    curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);

    /* return page content without header */
    //curl_setopt($this->_ch, CURLOPT_HEADER, 1);
    if (null != $header) {
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $header);
    }

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

?>
