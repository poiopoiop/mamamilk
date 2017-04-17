<?php

class Shannon {

    public function __construct() {
    }

    /*
     * 选择最好的数据集划分方式
     */
    public function chooseBestFeatureToSplit($data) {
        $numFeature = sizeof($data[0]) - 1;
        $dataCount = sizeof($data);
        $baseEntropy = $this->calcShannonEnt($data);

        $bestInfoGain = 0;
        $bestFeature  = -1;

        $newEntropy = array();
        $infoGain   = array();
        for ($axis=0; $axis<$numFeature; $axis++) {
            $set = $this->pickAllValueSet($data, $axis);
            foreach ($set as $key => $value) {
                $subDataSet = $this->dataSelector($data, $axis, $key);
                $prob = sizeof($subDataSet)/$dataCount;
                $newEntropy[$axis] += $prob*$this->calcShannonEnt($subDataSet);
            }
            $infoGain[$axis] = $baseEntropy - $newEntropy[$axis];
            if ($infoGain[$axis] > $bestInfoGain) {
                $bestInfoGain = $infoGain[$axis];
                $bestFeature = $axis;
            }
        }
        $ret = array(
            'best' => array (
                'feature'   => $bestFeature,
                'infoGain'  => $bestInfoGain,
            ),
            'baseEntropy'   => $baseEntropy,
            'newEntropy'    => $newEntropy,
            'infoGain'      => $infoGain,
        );
        return $ret;
    }

    /*
     * brief: 计算一组数据的熵
     *   针对输入数组的最后一列进行计算
     *   最后一列值的取值集合收敛
     */

    public function calcShannonEnt($data) {
        if (!is_array($data)) {
            return false;
        }

        $labalCounts = array();
        foreach ($data as $d) {
            $item = end($d);
            $labalCounts[$item] ++;
        }

        $shannonEnt = 0;
        $totalCount = sizeof($data);
        foreach ($labalCounts as $v){
            $prob = $v/$totalCount;
            $shannonEnt -= $prob * log($prob)/log(2);
        }

        return $shannonEnt;
    }

    /*
     * brief: 筛选出数据集中第$axis列等于$value的结合
     */
    public function dataSelector($data, $axis, $value) {
        $retDataSet = array();
        foreach ($data as $d) {
            if (trim($d[$axis]) === trim($value)) {
                $retDataSet[] = $d;
            }
        }
        return $retDataSet;
    }

    /*
     * 获取axis列的所有值的集合
     * 作为返回数组的key
     */
    public function pickAllValueSet($data, $axis) {
        $set = array();
        foreach ($data as $d) {
            $set[$d[$axis]] = 1;
        }
        return $set;
    }
    
};
