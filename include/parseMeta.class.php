<?php
    function parseMeta ($metaFile) {
        $ret = array();
        $lineNum = count($metaFile);
        for ($i=0; $i<$lineNum; $i++) {
            $pos = strpos($metaFile[$i], "��");
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
        '������`��' => 'book',
        '��Ʒ��' => 'title',
        '��Ʒ���i��' => 'title_trans',
        '������' => 'name',
        '�������i��' => 'pronunciation',
        '��`���ֱ�ӛ' => 'roman',
        '����' => 'birth',
        'û��' => 'die',
        '����ˤĤ���' => 'profile',
    );
?>
