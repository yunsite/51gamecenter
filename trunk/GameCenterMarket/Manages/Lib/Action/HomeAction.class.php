<?php
class HomeAction extends _PublicAction
{
    public function index()
    {	
    	//绑定标签
    	$w="home";
    	$this->assign("w",$w);
    	$num=2;
    	if($_GET['platform']==0){
    		$num=1;
    	}
    	$b="home_".$num;
    	$this->assign("b",$b);
    	
    	$type=isset($_POST['type'])?$_POST['type']:'0,1';
    	$type=(-1==$type?'0,1':$type);
    	$location=isset($_POST['location'])?$_POST['location']:'0,1,2';
    	$location=(-1==$location?'0,1,2':$location);
    	$data=array(
    	'type'=>($type=='0,1'?-1:$type),
    	'location'=>($location=='0,1,2'?-1:$location),		
    	);
    	$map['platform']=$_GET[platform];
    	$map['type']=array('in',"$type");
    	$map['location']=array('in',"$location");
		$db = M("home"); 
 		import("ORG.Util.Page"); 
 		$count = $db->where($map)->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','条记录');
 		$show = $Page->show(); 
		$list = $db->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		//echo $db->getLastSql();
		$this->assign('data',$data);
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }
	public function edit(){
		//绑定标签
    	$w="home";
    	$this->assign("w",$w);
    	
	    $db=M("home");
		if(!empty($_POST)){
			unset($_POST['__hash__']);
			if(!$db->data($_POST)->save()){
			throw_exception('修改失败');
			}
			header("Location: ?m=Home&a=index&platform=$_POST[platform]");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function del(){
		$db=M("home");
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M("home");
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		header("Location: ?m=Home&a=index&platform=$_GET[platform]");
	}
	public function add(){
		$w="home";
		$this->assign("w",$w);
		$b="home_3";
		$this->assign("b",$b);
		$db=M("home");
		if(!empty($_POST)){
			$_POST['add_date']=time();
			$ids=split(',',$_POST['t_id']);
			foreach ($ids as $id){
				$_POST['t_id']=$id;
				$db->data($_POST)->add();
			}
			//$db->data($_POST)->add();
			header("Location: ?m=Home&a=index&platform=$_POST[platform]");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function test(){
	echo APP_PATH.'<br>';
	echo __INFO__.'<br>';
	echo __APP__.'<br>';
	echo pathinfo().'<br>';
	} 
}
?>