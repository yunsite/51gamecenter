<?php


// 本文档自动生成，仅供测试运行
class MarketAction extends _PublicAction {

	public function index() {
//		
		$collection;
		$w = "category";
		$this->assign("w", $w);
		$b = "category_1";
		$this->assign("b", $b);
		$extraSelectKey1 = 1;
		$extraSelectKey2;
		if (!empty ($_POST)) {
			$key = $_POST['channelname'];
			if (!empty ($key)) {
				$extraSelectKey1 = "channelname='" . $key . "'";
			}

			$key = $_POST['gamename'];
//			var_dump($key);

			if (!empty ($key)) {
				$key = $this->getGameMappingArray($key);
				if (!empty ($key)) {
					$extraSelectKey2 = " and appKey='" . $key . "'";
				};
			}

			$key = $_POST['beginDate'];
			if (!empty ($key)) {
				
					$tmp=strtotime($key);
					$extraSelectKey2 = $extraSelectKey2." and submitTime>='" . $tmp . "'";
			}
			
			$key = $_POST['endDate'];
			if (!empty ($key)) {
				
					$tmp=strtotime($key);
					$extraSelectKey2 = $extraSelectKey2." and submitTime<='" . $tmp . "'";
			}
			
			$key = $_POST['servername'];
			if (!empty ($key)) {
					$extraSelectKey2 = $extraSelectKey2." and submitTime<='" . $tmp . "'";
			}
		
		}

		echo $extraSelectKey2;
		
		$Model = new Model(); // 实例化一个model对象 没有对应任何数据表
		$_selectKey = "select channelid, channelname from analytics WHERE " . $extraSelectKey1 . " group by analytics.channelId";
		//		echo $_selectKey;

		$collection = $Model->query($_selectKey);
		import("ORG.Util.Page");
		$count = count($collection);
		$Page = new Page($count, 50);
		$Page->setConfig('共', '个渠道');
		$show = $Page->show();
		$_selectKey = "select channelid, channelname from analytics WHERE " . $extraSelectKey1 . " group by analytics.channelId limit " . $Page->firstRow . "," . $Page->listRows;
		//		echo $_selectKey;
		$collection = $Model->query($_selectKey);

		$_selectKeyBase = "SELECT COUNT(*) as count FROM event WHERE 1";
		$_selectKeyBase = $_selectKeyBase . $extraSelectKey2;
		foreach ($collection as & $row) {
			//渠道id，名称
			$channelID = $row['channelid'];

			//终端人数
			$_selectKey = $_selectKeyBase . " and channelid='" . $channelID . "'";
//			echo $_selectKey . "\n";
			$collection1 = $Model->query($_selectKey);
			$row['usercount'] = $collection1[0]['count'];

			//注册人数
			$_selectKey = $_selectKeyBase . " and channelid='" . $channelID . "'" . "AND eventName='register_success'";
			$collection1 = $Model->query($_selectKey);
			$row['regcount'] = $collection1[0]['count'];

			//激活人数 eventname=User_active_game
			$_selectKey = $_selectKeyBase . " and channelid='" . $channelID . "'" . "AND eventName='User_active_game'";
			$collection1 = $Model->query($_selectKey);
			$row['activcount'] = $collection1[0]['count'];

			//有效用户
			$_selectKey = $_selectKeyBase . " and channelid='" . $channelID . "'" . "AND eventName='User_level' AND COUNT>=10 AND COUNT <20";
			$collection1 = $Model->query($_selectKey);
			$row['validcount'] = $collection1[0]['count'];

			//无效用户
			$row['invalidcount'] = $row['usercount'] - $row['activcount'];

			//在线人数
			$_selectKey = $_selectKeyBase . " and channelid='" . $channelID . "'" . "AND eventName='Online_player_number'";
			$collection1 = $Model->query($_selectKey);
			$row['onlinecount'] = $collection1[0]['count'];

			//二登
			$yestday = $this->getYestdayTimestmap();
			$today = $this->getTodayTimestmap();
			$_selectKey = $_selectKeyBase . " and channelid='" . $channelID . "'" . "AND starttime='" . $today . "' AND eventName='login_success' AND  EXISTS (" . $_selectKeyBase . " and channelid='" . $channelID . "' starttime='" . $yestday . "' AND eventName='register_success' )";
			$row['secondLogincount'] = $collection1[0]['count'];
		}

		$this->assign('list', $collection);
		$this->assign('page', $show);
		//var_dump($collection) ;
		$this->display();

	}

	function getYestdayTimestmap() {
		$yTimestamp = strtotime("-1 day");
		$date_time_array = getdate($yTimestamp);
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];
		$tmp = mktime(0, 0, 0, $month, $day, $year);
		return $tmp;
	}

	function getTodayTimestmap() {
		$yTimestamp = time();
		$date_time_array = getdate($yTimestamp);
		$month = $date_time_array["mon"];
		$day = $date_time_array["mday"];
		$year = $date_time_array["year"];
		$tmp = mktime(0, 0, 0, $month, $day, $year);
		return $tmp;
	}

	function getGameMappingArray($appname) {
		$gamearray = array (
			"风行天下" => "key123456789",
			"zhagnsan" => "key777777777"
		);
		//		var_dump($gamearray);
		return $gamearray[$appname];
	}

}
?>