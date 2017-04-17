<?php
class Host {
    public function hostname($ip) {
        $ret = exec("host $ip");
        //75.19.81.10.in-addr.arpa domain name pointer db-arch-redis68.db01.baidu.com.
        $pattern = "/pointer (.*)\.baidu\.com/";
        if (preg_match($pattern, $ret, $output)) {
            return $output[1];
        }
        
        return null;
    }
}
