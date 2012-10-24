<?php
// 本文档自动生成，仅供测试运行
class CostAction extends _PublicAction
{
    public function index()
    {
		//绑定标签
		$w="cost";
		$this->assign("w",$w);
		
		$b="cost_watch";
		$this->assign("b",$b);
		
		$db = M("costinfo"); 
 		import("ORG.Util.Page"); 
 		$count = $db->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','条花费记录');
 		$show = $Page->show(); 
		$list = $db->order('costid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }

	public function edit(){
		//绑定标签
		$w="cost";
		$this->assign("w",$w);
		$db=M("costinfo");
		
		if(!empty($_POST)){
			unset($_POST['__hash__']);
			$sql="update costinfo set channelid=$_POST[channelid],appid=$_POST[appid],createtime=$_POST[createtime],cost=$_POST[cost] where costid=$_POST[costid]";
			$db->query($sql);
			header("Location: ?m=Cost&a=index");
		}else{
//			$list=$db->getById($_GET[costid]);
			$sql="select * from costinfo where costid=$_GET[costid] limit 0,1";
			$list=$db->query($sql);
			$this->assign("list",$list[0]);
			$this->display();
		}
	}
	public function del(){
		$db=D("costinfo");
		$sta=$db->where("costid = '$_GET[costid]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M("costinfo");
		for($i=0;$i<count($_POST['dels']);$i++){ 
			$id = $_POST['dels'][$i];
			echo $id;
			$db->where("costid = '$id'")->delete();
		}
		header("Location: ?m=Cost&a=index");
		
	}
	public function add(){
		$w="cost";
		$this->assign("w",$w);
		$b="cost_add";
		$this->assign("b",$b);
		$db=M("costinfo");
		var_dump($_POST);
		if(!empty($_POST)){
//			$channelid=$_POST[channelid];
//			$sql = "select id from channelinfo where channelid=$channelid";
			$db->data($_POST)->add();
//			echo $db->getlastsql();
//			exit;
			header("Location: ?m=Cost&a=index");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
}
?>