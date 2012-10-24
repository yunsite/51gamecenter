<?php
// 本文档自动生成，仅供测试运行
class TypeAction extends _PublicAction
{
    public function index()
    {
		//绑定标签
		$w="type";
		$this->assign("w",$w);
		$b="type_1";
		$this->assign("b",$b);
		
		$db = M("type"); 
 		import("ORG.Util.Page"); 
 		$count = $db->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','个分类');
 		$show = $Page->show(); 
		$list = $db->order('inputtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }
	public function edit(){
		//绑定标签
		$w="type";
		$this->assign("w",$w);
		$db=M("type");
		if(!empty($_POST)){
			$db->data($_POST)->save();
			$this->redirect("Type/index");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function del(){
		$db=M("type");
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M("type");
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		$this->redirect("Type/index");
	}
	public function add(){
		$w="type";
		$this->assign("w",$w);
		$db=M("type");
		if(!empty($_POST)){
			$_POST['inputtime']=date("Y-m-d H:i:s");
			$db->data($_POST)->add();
			$this->redirect("Type/index");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}

}
?>