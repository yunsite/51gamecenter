<?php
class ContentAction extends _PublicAction
{
    public function index()
    {
		//绑定标签
		$w="content";
		$this->assign("w",$w);
		$b="content_1";
		$this->assign("b",$b);
		
		$db = M("content"); 
 		import("ORG.Util.Page"); 
 		$count = $db->count(); 
 		$Page = new Page($count,10);
		$Page->setConfig('header','篇文章');
 		$show = $Page->show();
		$list = $db->order('inputtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
    }
	public function add(){
		//绑定标签
		$w="content";
		$this->assign("w",$w);
		$b="content_2";
		$this->assign("b",$b);
		if(!empty($_POST)){
			$db=M("content");
			$_POST[inputtime]=date("Y-m-d H:i:s");
			$list=$db->data($_POST)->add();
			$this->redirect("Content/index");
		}else{
			$db=M("type");
			$list=$db->select();
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function del(){
		$db=M("content");
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M("content");
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		$this->redirect("Content/index");
	}
	public function edit(){
		//绑定标签
		$w="content";
		$this->assign("w",$w);
		$db=M("content");
		if(!empty($_POST)){
			$db->data($_POST)->save();
			dump();
			$this->redirect("Content/index");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			
			$db2=M("type");
			$list2=$db2->select();
			$this->assign("list2",$list2);
			
			$this->display();
		}
	}
	public function show_pl(){
		//绑定标签
		$w="content";
		$this->assign("w",$w);
		
		$db=M("content");
		$list2 = $db->getById($_GET['id']);
		$this->assign("list2",$list2);
		
		$db2 = M("comment");
 		import("ORG.Util.Page"); 
 		$count = $db2->where("blog_id = '$_GET[id]'")->count(); 
 		$Page = new Page($count,10);
		$Page->setConfig('header','个回复');
 		$show = $Page->show();
		$list = $db2->where("blog_id = '$_GET[id]'")->order('inputtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		
		$this->display();
	}
	public function del_pl(){
		$db=M("comment");
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels_pl(){
		$db=M("comment");
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		$this->redirect("Content/index");
	}
	public function doCallbank(){
		$db=M("comment");
		$db->where("id = '$_POST[ids]'")->data($_POST)->save();
		$this->redirect("Content/show_pl/id/".$_POST['re_id']);
	}

}
?>