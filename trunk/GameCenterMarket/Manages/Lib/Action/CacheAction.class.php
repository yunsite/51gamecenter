<?php
include(ROOT_PATH."/Manages/Common/common.class.php");
class CacheAction extends _PublicAction
{

	public function index(){
		//绑定标签
		$w="cache";
		$this->assign("w",$w);
		
		$num=2;
		if($_GET['platform']=='android'){
			$num=2;
		}else if($_GET['platform']=='ios'){
			$num=1;
		}
		$b="cache_".$num;
		$this->assign("b",$b);
		$this->display();
	}
	public function del(){
		$platform=$_GET['platform'];
		$cacheName="";
		$result="";
		//处理首页缓存  "gc_home_".$_GET['location']."_".$_GET['platform']."_".$this->itemscount;
		$home=$_POST['home'];
		$result.="<b>首页缓存清理...</b><br/>";
		foreach ($home as $row){
			switch($row){
				case 'round':$cacheName="gc_home_round_".$_GET['platform']."_4";break;
				case 'game':$cacheName="gc_home_game_".$_GET['platform']."_10";break;
				case 'topic':$cacheName="gc_home_topic_".$_GET['platform']."_10";break;
			}
			Common::setCache($cacheName,"");
			$result.=$cacheName."<br/>";
		}
		//处理大厅缓存  "gc_hot_".$_GET['type']."_".$_GET['platform']."_".$this->id."_".$this->itemscount."_".$this->cursor
		$hot=$_POST['hot'];
		$result.="<b>大厅缓存清理...</b><br/>";
		//"gc_all_hot_key_".$_GET['type']."_".$_GET['platform']
		foreach ($hot as $row){
			switch($row){
				case 'new':$cacheName="gc_all_hot_key_new_".$_GET['platform'];break;
				case 'rank':$cacheName="gc_all_hot_key_rank_".$_GET['platform'];break;
				case 'recommended':$cacheName="gc_all_hot_key_recommended_".$_GET['platform'];break;
			}
			$res=Common::getCache($cacheName);
			if(is_array($res)){
				foreach($res as $r){
					Common::setCache($r, "");
					$result.=$r."<br/>";
				}
			}
		}
		
		//处理分类缓存  "gc_category_".$_GET['platform']."_".$this->itemscount."_".$this->cursor;
		//"gc_category_".$_GET['id']."_".$type."_".$_GET['platform']."_".$this->itemscount."_".$this->cursor
		$category=$_POST['category']; //cate  cate_data
		$result.="<b>分类缓存清理...</b><br/>";
		//cache的key  分别是 
		//"gc_all_category_key_".$_GET['platform']   
		//"gc_all_category_data_key_".$_GET['platform']
        foreach($category as $row){
        	if('cate'==$row){
        		$cacheName="gc_all_category_key_".$platform;
        	}else{
        		$cacheName="gc_all_category_data_key_".$platform;
        	}
        	$res=Common::getCache($cacheName);
        	if(is_array($res)){
        		foreach($res as $r){
        			Common::setCache($r, "");
        			$result.=$r."<br/>";
        		}
        	}
        }		
		
		
		//处理专题缓存  "gc_topic_".$_GET['platform']."_".$this->itemscount."_".$this->cursor
		//"gc_topicitem_".$this->topic_id."_".$this->itemscount."_".$this->cursor
		$topic=$_POST['topic'];
		$result.="<b>专题缓存清理...</b><br/>";
		//"gc_all_topic_key_".$_GET['platform']
		//"gc_all_topic_data_key_".$_GET['platform']
		foreach($topic as $row){
			if('topic'==$row){
				$cacheName="gc_all_topic_key_".$platform;
			}else{
				$cacheName="gc_all_topic_data_key_".$platform;
			}
			$res=Common::getCache($cacheName);
			if(is_array($res)){
				foreach($res as $r){
					Common::setCache($r, "");
					$result.=$r."<br/>";
				}
			}
		}
		
		//关键词缓存
		//"gc_all_keyword_key_".$_GET['platform']  gc_all_keyword_key_ios
		$result.="<b>关键词缓存清理...</b><br/>";
		if(isset($_POST['keyword'])&&'keyword'==$_POST['keyword']){
			$cacheName="gc_all_keyword_key_".$platform;
			$res=Common::getCache($cacheName);
			if(is_array($res)){
				foreach($res as $r){
					echo $r;
					Common::setCache($r, "");
					$result.=$r."<br/>";
				}
			}
		}
		
		//单个游戏数据缓存(不缓存所有key)
		//"gc_game_".$_GET['platform']."_".$this->id
		$result.="<b>游戏缓存清理...</b><br/>";
		if(''!=$_POST['game']&&null!=$_POST['game']){
			$games=explode(',',$_POST['game']);
			foreach($games as $game){
				$cacheName="gc_game_".$platform."_".$game;
				Common::setCache($cacheName, "");
				$result.=$cacheName."<br/>";
			}
		}
		
		//自定义数据缓存
		$result.="<b>自定义缓存清理...</b><br/>";
		if(''!=$_POST['cache']&&null!=$_POST['cache']){
			$caches=explode(',',$_POST['cache']);
			foreach($caches as $cache){
				Common::setCache($cache, "");
				$result.=$cache."<br/>";
			}
		}
		
		//清理IOS限免缓存    "gc_limit_".$_GET['platform']."_".$this->itemscount."_".$this->cursor
		if("ios"==$platform){
			$result.="<b>IOS限免缓存清理...</b><br/>";
			$limit=isset($_POST['limit'])?$_POST['limit']:"";
			if('limit'==$limit){
               $cacheName="gc_all_limit_key_".$platform;
               $res=Common::getCache($cacheName);
               if(is_array($res)){
               	  foreach($res as $r){
               		  Common::setCache($r, "");
               		  $result.=$r."<br/>";
               	  }
               }				
			}
		}
		$this->assign('result',$result);
		$this->display('index');
	}
}
?>