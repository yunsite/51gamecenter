<?php
function delCache($dir="Cache") {
    //打开文件目录
    $dh = opendir($dir);
    //循环读取文件
    while ($file = readdir($dh)) {
        if($file != '.' && $file != '..') {
            $fullpath = $dir . '/' . $file;
            //判断是否为目录
            if(!is_dir($fullpath)) {
                //如果不是,删除该文件
                if(!unlink($fullpath)) {
					
                }
            } else {
                //如果是目录,递归本身删除下级目录
                delCache($fullpath);
            }
        }
    }
    //关闭目录
    closedir($dh);
    /*//删除目录
    if(rmdir($dir)) {
       return true;
    } else {
       return false;
    }*/
}
function getCate($id,$isAll=false){
	$aCate=array (
  82 => '测试3333',
  81 => '测试测试',
  80 => '测试234',
  77 => '攻略宝典',
  76 => '模拟辅助',
  75 => '趣味软件',
  74 => '益智游戏',
  73 => '休闲游戏',
  72 => '美女游戏',
  71 => '音乐游戏',
  70 => '解迷游戏',
  69 => '塔防策略',
  68 => '经营养成',
  67 => '体育竞速',
  66 => '射击飞行',
  65 => '网游在线',
  64 => '角色扮演',
  63 => '棋牌游戏',
  62 => '动作冒险',
  61 => '游戏大厅',
  60 => '游戏大厅',
  50 => '策略',
  49 => '体育',
  48 => '模拟',
  47 => '角色',
  46 => '赛车',
  45 => '益智',
  44 => '其他',
  43 => '养成',
  42 => '竞速',
  41 => '休闲',
  40 => '回合',
  39 => '即时',
  38 => '棋牌',
  37 => '射击',
  36 => '格斗',
  35 => '冒险',
  34 => '动作',
);
	if($isAll){
		return $aCate;
	}
	return $aCate[$id];
}
function getCateByPlat($platform=0){
	$db = M("category"); 
	$list =$db->where('platform ='.$platform)->order('id desc')->select();
	$ret=array();
	foreach($list as $k=>$v){
		$ret[$v['id']]=$v['name'];
	
	}
	return $ret;
}
function substr_for_utf8($sourcestr, $cutlength) {
	$returnstr = "";
	$i = 0;
	$n = 0;
	$str_length = strlen ( $sourcestr ); //字符串的字节数
	while ( ($n < $cutlength) and ($i <= $str_length) ) {
		$temp_str = substr ( $sourcestr, $i, 1 );
		$ascnum = Ord ( $temp_str ); //得到字符串中第$i位字符的ascii码
		if ($ascnum >= 224) //如果ASCII位高与224，
       {
			$returnstr = $returnstr . substr ( $sourcestr, $i, 3 ); //根据UTF-8编码规范，将3个连续的字符计为单个字符
			$i = $i + 3; //实际Byte计为3
			$n ++; //字串长度计1
		} elseif ($ascnum >= 192) //如果ASCII位高与192，
        {
			$returnstr = $returnstr . substr ( $sourcestr, $i, 2 ); //根据UTF-8编码规范，将2个连续的字符计为单个字符
			$i = $i + 2; //实际Byte计为2
			$n ++; //字串长度计1
		} elseif ($ascnum >= 65 && $ascnum <= 90) //如果是大写字母，
        {
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; //实际的Byte数仍计1个
			$n ++; //但考虑整体美观，大写字母计成一个高位字符
		} else //其他情况下，包括小写字母和半角标点符号，
        {
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; //实际的Byte数计1个
			$n = $n + 0.5; //小写字母和半角标点等与半个高位字符宽…
		}
	}
	if ($str_length > $cutlength) {
		$returnstr = $returnstr . "…"; //超过长度时在尾处加上省略号
	}
	return $returnstr;
}


