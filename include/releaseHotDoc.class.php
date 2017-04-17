<?php
//用于区分pc端和wap端生成的热门榜单文件
$GLOBALS['hot_doc_list'] = array(
    'hot_doc'       => array('table_name' => 'hot_doc',
                    'class_name' => '',
                    'title' => 'HOT_DOC_LIST',
                    'conf_file' => 'HotDocList.conf.php',
                    'least_num' => '5',
                    'type' => 'conf',),
    'hot_doc_wap'   => array('table_name' => 'hot_doc_wap',
                    'class_name' => 'TopReadList',
                    'title' => 'TOP_READ_LIST',
                    'conf_file' => 'TopReadList.class.php',
                    'least_num' => '20',
                    'type' => 'class',),
    'rec_doc_wap'   => array('table_name' => 'rec_doc_wap',
                    'class_name' => 'RecommendList',
                    'title' => 'RECOMMEND_LIST',
                    'conf_file' => 'RecommendList.class.php',
                    'least_num' => '60',
                    'type' => 'class',),
    'hot_read'      => array('table_name' => 'hot_read',
                    'class_name' => 'HotReadList',
                    'title' => 'HOT_READ_LIST',
                    'conf_file' => 'HotReadList.class.php',
                    'least_num' => '20',
                    'type' => 'class',),
    'hot_album_wap' => array('table_name' => 'hot_album_wap',
                    'class_name' => 'HotAlbumList',
                    'title' => 'HOT_ALBUM_LIST',
                    'conf_file' => 'HotAlbumList.class.php',
                    'least_num' => '5',
                    'type' => 'album',),
);

	//发布已挑选的热门文档
	function releaseHotDoc($strDocId, $type)
	{
		if ("conf" == $GLOBALS["hot_doc_list"][$type]["type"]) {
			$output = '<?php'."\n\n". '$GLOBALS["'.$GLOBALS['hot_doc_list'][$type]['title'].
				'"] = array( '."\n";

			if (empty($strDocId)) {
				$logger->error("paramer strdocid is null", __FILE__, __LINE__, 0);
				return false;
			}
		
			$result = getDocInfo($strDocId);
			if (false === $result) {
				echo "get release hot doc failed, type=$type\n";
				return false;
            }

			//逐个导出
			for($i=0; $i<count($result); $i++) {
				$output .= "\tarray( \n";
				$output .= "\t\t'doc_id' => '".$r['doc_id']."',\n";
				$output .= "\t\t'title'  => ".var_export($r['title'], TRUE).",\n";
				$output .= "\t\t'type'   => '".$r['type']."',\n";
				$output .= "\t\t'uid'    => '".$r['uid']."',\n";
				$output .= "\t\t'uname'  => ".var_export($r['uname'], TRUE).",\n";
				$output .= "\t),\n";
			}

			$output .= ");\n\n?>";
		}
		elseif ("class" == $GLOBALS["hot_doc_list"][$type]["type"]) {
			$output = '<?php'."\n\n". "class ".$GLOBALS['hot_doc_list'][$type]['class_name']
				."\n"."{"
				."\n"."\tpublic static $".$GLOBALS['hot_doc_list'][$type]['title'].";"
				."\n"."}"
				."\n\n".$GLOBALS['hot_doc_list'][$type]['class_name']."::$".$GLOBALS['hot_doc_list'][$type]['title']
				." = array("."\n";

			if (empty($strDocId)) {
				echo "paramer strdocid is null\n";
				return false;
			}

			$result = getDocInfo($strDocId);
			if (false === $result) {
				echo "get release hot doc failed, type=$type\n";
				return false;
			}

			//逐个导出
			$k = iconv ('GBK//IGNORE', 'UTF-8', '千');
            while ($r = mysql_fetch_row($result)) {
				$title = iconv('GBK//IGNORE', 'UTF-8', $r['12']);
				$size = number_format($r['10']/1024, 1);
				if ("0.0" == $size) {
					$size = 0.1;
				}
				
				$download = $r['24'];
				if ($download >= 1000) {
					
					$download = number_format($download/1000, 1).$k;
				}
				$uname = iconv('GBK//IGNORE', 'UTF-8', $r['15']);


				
				$output .= "\tarray( \n";
				$output .= "\t\t'doc_id' => '".$r['0']."',\n";
				$output .= "\t\t'title'  => ".var_export($title, TRUE).",\n";
				$output .= "\t\t'type'   => '".$r['8']."',\n";
				$output .= "\t\t'uid'    => '".$r['14']."',\n";
				$output .= "\t\t'size'    => '".$size."',\n";
				$output .= "\t\t'size_iphone'    => '".$r['10']."',\n";
				$output .= "\t\t'download_count'    => '".$download."',\n";
				$output .= "\t\t'download_count_iphone'    => '".$r['24']."',\n";
				$output .= "\t\t'uname'  => ".var_export($uname, TRUE).",\n";
				$output .= "\t\t'value_count'	=> '".$r['25']."',\n";
				$output .= "\t\t'value_average'	=> '".$r['28']."',\n";
				$output .= "\t),\n";
			}

			$output .= ");\n\n?>";

		}
			
        file_put_contents($GLOBALS["hot_doc_list"][$type]["conf_file"], $output);
		echo "exportFile $fileName success\n";

		return true;
	}
