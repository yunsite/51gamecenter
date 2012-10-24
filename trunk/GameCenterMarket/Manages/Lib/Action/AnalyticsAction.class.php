<?php
// 本文档自动生成，仅供测试运行
class AnalyticsAction extends _PublicAction
{
    public function index()
    {		
		//绑定大标签
		$w="operate";
		$this->assign("w",$w);	

		$b="analytics_";
		$this->assign("b",$b);
		
		$db = M("analytics"); 
 		import("ORG.Util.Page"); 
 	    $b_platform=false;
 	    $b_carrier=false;
 	    $b_amount=false;
		
		if(isset($_REQUEST['platform'])||isset($_REQUEST['carrier'])){
			
			$platform=$_REQUEST['platform'];
			$carrier=$_REQUEST['carrier'];
			$amount=$_REQUEST['amount'];
			
			$where="";
			if($platform!=0){
				//下拉框里的id分别为1和2，所以对应分别要减去1
				$platform=$platform-1;
				$where="platform=$platform";
				$b_platform=true;
//				$Page->parameter.="&platform=".urlencode($platform);
			}

			if($carrier!=0){
				$b_carrier=true;
				if($where==""){
					$where="carrier='$carrier'";
				}else{
					$where=$where." and "."carrier='$carrier'";
				}
//				$Page->parameter.="&carrier=".urlencode($carrier);
			}
			if($amount!=0){
				$amount=true;
				if($where==""){
					$where="amount=$amount";
				}else{
					$where=$where." and "."amount=$amount";
				}
//				$Page->parameter.="&amount=".urlencode($amount);
			}
			
		}		
		$count = $db->where($where)->count(); 
		//让分页的数据也保证是参数查找出来的
		if($b_platform){			
			$Page->parameter.="&platform=".urlencode($platform);
		}
 		if($b_carrier){			
			$Page->parameter.="&carrier=".urlencode($carrier);
		}
		if($b_amount){			
			$Page->parameter.="&amount=".urlencode($amount);
		}
 		$Page = new Page($count,10); 
		$Page->setConfig('header','条记录');
			
		$show = $Page->show();
 		
 		//echo $db->getlastsql();
	  
		$list=$db->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }
}
?>