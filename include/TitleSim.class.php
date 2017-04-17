<?php
class TitleSim {
	public function get_title_sim_rate($title , $sim_doc_title){
		if($title == $sim_doc_title){//title1 == title2
			return 1;
		}

		$title_len = strlen($title);
		$sim_title_len = strlen($sim_doc_title);
		$max_len = 0;
		$min_len = 0;
		$max_title = "";
		$min_title = "";

		if($title_len > $sim_title_len){
			$max_len = $title_len;
			$min_len = $sim_title_len;

			$max_title = $title;
			$min_title = $sim_doc_title;
		}
		else{
			$max_len = $sim_title_len;
			$min_len = $title_len;

			$max_title = $sim_doc_title;
			$min_title = $title;
		}

		if($max_len == 0){
			return -1;
		}

		if($min_len * 2 > $max_len && (strpos($max_title,$min_title) !== false) ){//(title1.find(title2) != -1 and 2*len2 > len1)  or  (title2.find(title1) != -1 and 2*len1 > len2)
			return 0.5;
		}
		else if($min_len * 3 > $max_len && (strpos($max_title,$min_title) !== false)){
			return 0.3;
		}


		//Comlen/max(len1,len2))*(min(len1,len2)/max(len1,len2)
		$title_common_len = $this->get_common_len($sim_doc_title, $title, "gbk");
		return $title_common_len * 1.0 / $max_len * ($min_len * 1.0 / $max_len);
	}

	public function get_common_len($source, $target, $encoding){
	    global $logger;
	    $target = str_replace(" ", "", $target);
	    $source = str_replace(" ", "", $source);
	    $len = mb_strlen($source, $encoding);

	    $target = str_replace("<em>", "", $target);
	    $target = str_replace("</em>", "", $target);

	    $tlen = mb_strlen($target, $encoding);

	    if ($len == 0 || $tlen == 0){
            //$logger->addLog("WARNING", "sim:string length = 0");
	        return 0;
	    }

	    $patternlen = 0;
	    $start = 0;

	    for ($i = 0; $i != $len; ++$i){
	        if ($start >= $tlen){
	            break;
	        }

	        $nextc = mb_substr($source, $i, 1, $encoding);
	        $idx = mb_strpos($target, $nextc, $start, $encoding);
	        if ($idx === false){
	            continue;
	        }

	        $start = $idx + 1;
	        $patternlen += 1;
	    }

		$max_comm_len = $patternlen;

		$patternlen = 0;
		$start = 0;
	    for ($i = 0; $i != $tlen; ++$i){
	        if ($start >= $len){
	            break;
	        }

	        $nextc = mb_substr($target, $i, 1, $encoding);
	        $idx = mb_strpos($source, $nextc, $start, $encoding);

	        if ($idx === false){
	            continue;
	        }

	        $start = $idx + 1;
	        $patternlen += 1;
	    }
	    return $max_comm_len > $patternlen ? $max_comm_len : $patternlen;
    }





}
