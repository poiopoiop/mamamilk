<?php

require_once("/home/iknow/workroot/include/yylib/yycommon.conf.php");

class Draw {
private $_yylib;

public function __construct() {
    $this->_yylib = new yydraw();
    return;
}

//3组标题，字符串
public function setTitle($title, $xTitle, $yTitle) {
    $this->_yylib->setPicTitle($title);
    $this->_yylib->setXTitle($xTitle);
    $this->_yylib->setYTitle($yTitle);
    return;
}

//数组，元素个数需要与数据点数一致
//部分labal可以set为""，以减少横坐标数量
public function setXLables($xLables) {
    $this->_yylib->setPicLabels($xLables);
    return;
}

//一次set一条曲线的数据
public function addDataSet($lable, $value) {
    $this->_yylib->addDataSet($lable,$value);
    return;
}

public function getLine() {
    $line = $this->_yylib->drawLine();
    return $line;
}



}
