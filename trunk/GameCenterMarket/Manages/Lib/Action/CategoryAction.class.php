<?php
// 本文档自动生成，仅供测试运行
class CategoryAction extends _PublicAction
{
    public function index()
    {
		//绑定标签
		$w="category";
		$this->assign("w",$w);
		$num=2;
		if($_GET['platform']==1){
		$num=2;
		}else if($_GET['platform']==0){
		$num=1;
		}
		$b="category_".$num;
		$this->assign("b",$b);
		
		$db = M("category"); 
 		import("ORG.Util.Page"); 
 		$count = $db->where("platform ='$_GET[platform]'")->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','个分类');
 		$show = $Page->show(); 
		$list = $db->where("platform ='$_GET[platform]'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }
	/*public function index2()
    {
		//绑定标签
		$w="category";
		$this->assign("w",$w);
		$b="category_2";
		$this->assign("b",$b);
		
		$db = M("category"); 
 		import("ORG.Util.Page"); 
 		$count = $db->where("platform =1")->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','个分类');
 		$show = $Page->show(); 
		$list = $db->where("platform =1")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display('Category/index');
    }*/
	public function edit(){
		//绑定标签
		$w="category";
		$this->assign("w",$w);
		$db=M("category");
		if(!empty($_POST)){
			//var_dump($_POST);
			unset($_POST['__hash__']);
			//var_dump($_POST);
			if(!$db->data($_POST)->save()){
			echo "test";die;
			}

			header("Location: ?m=Category&a=index&platform=$_POST[platform]");
			//header("Location: __APP__?m=Category&a=index&platform=$_POST[platform]");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function del(){
		$db=M("category");
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M("category");
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		header("Location: ?m=Category&a=index&platform=$_GET[platform]");
		//header("Location: __APP__?m=Category&a=index&platform='$_GET[platform]'");
		//$this->redirect("?m=Category&a=index");
	}
	public function add(){
		$w="category";
		$this->assign("w",$w);
		$b="category_3";
		$this->assign("b",$b);
		$db=M("category");
		if(!empty($_POST)){
			//var_dump($_POST);
			//die;
			//$_POST['inputtime']=date("Y-m-d H:i:s");
			$db->data($_POST)->add();
			header("Location: ?m=Category&a=index&platform=$_POST[platform]");
			//$this->redirect(__APP__."?m=Category&a=index");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function test(){
		/*
	echo APP_PATH.'<br>';
	echo __INFO__.'<br>';
	echo __APP__.'<br>';
	echo pathinfo().'<br>';
	echo getCate('234').'<br>';
	*/
	$db = M("category"); 
	$list =$db->order('id desc')->select();
	$ret=array();
	foreach($list as $k=>$v){
		$ret[$v['id']]=$v['name'];
	
	}
	//$ret=$list;
	var_export($ret);
	//var_dump($ret);
	} 

}
?>