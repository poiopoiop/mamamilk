<?php
    function parseMeta ($metaFile) {
        $ret = array();
        $lineNum = count($metaFile);
        for ($i=0; $i<$lineNum; $i++) {
            $pos = strpos($metaFile[$i], "：");
            $key_jp = substr($metaFile[$i], 0, $pos);
            $value = trim(substr($metaFile[$i], $pos+2));

            if(isset($GLOBALS['keys'][$key_jp])) {
                $key = $GLOBALS['keys'][$key_jp];
                $ret[$key] = $value;
            }
            else {
                logger('ERR', 'unknown key:'.$key_jp);
            }
            //echo $key."|".$value."\n";
        }
        return $ret;
    }

    $GLOBALS['keys'] = array (
        '図書カード' => 'book',
        '作品名' => 'title',
        '作品名読み' => 'title_trans',
        '作家名' => 'name',
        '作家名読み' => 'pronunciation',
        'ローマ字表記' => 'roman',
        '生年' => 'birth',
        '没年' => 'die',
        '人物について' => 'profile',
    );
?>
