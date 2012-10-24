<?php
class GCbgStatisticsAction extends _PublicAction
{
    public function index()
    {		
		//绑定标签
		$w="operate";
		$this->assign("w",$w);	
		
		$b="statistics_";
		$this->assign("b",$b);
		
		$db = M("analytics"); 
 		import("ORG.Util.Page"); 
 	    
// 	    echo strtotime("2012-10-17");
// 	    echo date("Y-m-d",5607570047);
		
		if(isset($_REQUEST['$startTime'])||isset($_REQUEST['endtime'])){
			
			$channel=strtotime($_REQUEST['channel']);
			$game=strtotime($_REQUEST['game']);
			$server=strtotime($_REQUEST['server']);			
			$$startTime=strtotime($_REQUEST['$startTime']);
			$endTime=strtotime($_REQUEST['endtime']);
			$payStartTime=strtotime($_REQUEST['pay$startTime']);
			$payEndTime=strtotime($_REQUEST['payendtime']);
			
			$where="";

		}		
//		$count = $db->where($where)->count(); 
		
 		//echo $db->getlastsql();
 		//var_dump($collection1);
 		$event=M('analytics');
 		$sql = "select channelId, channelName from analytics group by channelId";
 		$list=$event->query($sql);
 				
// 		var_dump($alldata);

 		foreach ($list as &$row) {
			$channelId = $row['channelId'];

			//终端联网人数
			$sql = "SELECT COUNT(DISTINCT udid) as terminalOnLineNum FROM event WHERE channelId='".$channelId."'";
			if($startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}			
			$terminalOnLineNum = $event->query($sql);			
			$row['terminalOnLineNum']=$terminalOnLineNum[0]['terminalOnLineNum'];
			
			//注册人数
			$sql = "SELECT COUNT(DISTINCT udid) as registerNum FROM event WHERE channelId='".$channelId."'"." and eventName='register_success'";
			if($startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$registerNum = $event->query($sql);			
			$row['registerNum']=$registerNum[0]['registerNum'];
			
			//激活人数
			$sql = "SELECT COUNT(DISTINCT udid) as activityNum FROM event WHERE channelId='".$channelId."'"." and "."eventName='login_success'";
			if($startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$activityNum = $event->query($sql);			
			$row['activityNum']=$activityNum[0]['activityNum'];
			
			//有效用户
			$sql = "SELECT COUNT(DISTINCT udid) as effectUserNum FROM event WHERE channelId='".$channelId."'"." and eventType='user' and count>10";
			if($startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$effectUserNum = $event->query($sql);			
			$row['effectUserNum']=$effectUserNum[0]['effectUserNum'];
			
			//无效用户
			$sql = "SELECT COUNT(DISTINCT udid) as unEffUserNum FROM event WHERE channelId='".$channelId."'"." and "."eventType='user' and count=1";
			if($startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$unEffUserNum = $event->query($sql);			
			$row['unEffUserNum']=$unEffUserNum[0]['unEffUserNum'];
			
			//在线人数
			$sql = "SELECT COUNT(DISTINCT udid) as onlineNum  FROM event WHERE channelid='".$channelId."'"." and eventName='login_success'";
			if($$startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$onlineNum = $event->query($sql);			
			$row['onlineNum']=$onlineNum[0]['onlineNum'];
			
			//二登人数
			$num=0;
			if($startTime!=null && $endTime!=null){
				if($startTime>$endTime){
					exit;
				}
				for($i=$$startTime-86400;$i<$endTime;$i+=86400){
    			$sql = "SELECT COUNT(DISTINCT udid) as twoLoginNum  FROM event " .
    					"WHERE (SELECT (DISTINCT udid) FROM event WHERE eventName='register_success' and $i<startTime and startTime<($i+86400)) " .
    					"in (SELECT (DISTINCT udid) FROM event WHERE eventName='login_success' and ($i+86400)<startTime and startTime<($i+86400*2))";
    			
    			$twoLoginNum=$event->query($sql);
    			$num+=$twoLoginNum[0]['twoLoginNum'];
    			}	
			}			
			$row['twoLoginNum']=$num;
			
			//广告费用	
			$sql = "select sum(cost) as advertisementCost from costinfo where channelId='".$channelId."'";
			if($startTime!=null && $endTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$advertisementCost = $event->query($sql);			
			$row['advertisementCost']=$advertisementCost[0]['advertisementCost'];
			if($row['advertisementCost']==null){
				$row['advertisementCost']=0;
			}
			
			//二登成本
			if($row['twoLoginNum']==0){
				$row['twoLoginCost']=0;			
			}else{
				$row['twoLoginCost']=$row['advertisementCost']/$row['twoLoginNum'];
			}			
						
			//注册成本
			if($row['registerNum']==0){
				$row['registerCost']=0;				
			}else{
				$row['registerCost']=$row['advertisementCost']/$row['registerNum'];
			}			
						
			//激活成本	
			if($row['activityNum']==0){
				$row['activityCost']=0;			
			}else{
				$row['activityCost']=$row['advertisementCost']/$row['activityNum'];
			}
			
			//有效用户成本
			if($row['effectUserNum']==0){
				$row['effectUserCost']=0;					
			}else{
				$row['effectUserCost']=$row['advertisementCost']/$row['effectUserNum'];
			}
					
			//激活转化率	
			if($row['terminalOnLineNum']==0){
				$row['activityEffect']=$row['activityNum'];
			}else{
				$row['activityEffect']=$row['activityNum']/$row['terminalOnLineNum'];
			}		
						
			//注册转化率
			if($row['terminalOnLineNum']==0){
				$row['registerEffect']=$row['registerNum'];
			}else{
				$row['registerEffect']=$row['registerNum']/$row['terminalOnLineNum'];
			}
						
						
			//充值人数
			$sql = "SELECT COUNT(DISTINCT udid) as payNum FROM event WHERE channelId='".$channelId."'"." and eventType='pay'";
			if($payStartTime!=null && $payEndTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$payNum = $event->query($sql);			
			$row['payNum']=$payNum[0]['payNum'];
			
			//充值金额
			$sql = "SELECT SUM(count) as moneyNum FROM event WHERE channelId='".$channelId."'";
			if($payStartTime!=null && $payEndTime!=null){
				$sql = $sql." and startTime>=$startTime"." and endTime<=$endTime";
			}
			$moneyNum = $event->query($sql);			
			$row['moneyNum']=$moneyNum[0]['moneyNum'];
			if($row['moneyNum']==null){
				$row['moneyNum']=0;
			}
			
			//投入回报率	
			if($row['advertisementCost']==0){
				$row['paybackEffect']=1;
			}else{
				$row['paybackEffect']=$row['moneyNum']/$row['advertisementCost'];
			}	
			
		}	

		if($startTime!=null){			
			$Page->parameter.="&startTime=".urlencode($startTime);
		}

		if($endTime!=null){			
			$Page->parameter.="&endtime=".urlencode($endTime);
		}

		if($b_amount){			
			$Page->parameter.="&amount=".urlencode($amount);
		}

		$count=count($list);
 		$Page = new Page($count,10); 
		$Page->setConfig('header','条记录');
		$show = $Page->show();

//		$list=$event->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('amount',$amount);
        $this->display();
    }
  
}
?>