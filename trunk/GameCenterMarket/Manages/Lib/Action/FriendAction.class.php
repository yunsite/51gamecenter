<?php
class FriendAction extends _PublicAction
{
    public function index()
    {
		//绑定标签
		$w="friend";
		$this->assign("w",$w);
		$b="friend_1";
		$this->assign("b",$b);
		
		$db = M("friend"); 
 		import("ORG.Util.Page"); 
 		$count = $db->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','个友情链接');
 		$show = $Page->show(); 
		$list = $db->order('inputtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list); 
		$this->assign('page',$show);
		$this->display();
    }
	public function add(){
		//绑定标签
		$w="friend";
		$this->assign("w",$w);
		$b="friend_2";
		$this->assign("b",$b);
		if(!empty($_POST)){
			$img=$this->_UpImg($_POST['img'],"./UploadFiles/friendsLogo/",false);
			$_POST['img'] = $img['savename'];
			$db=M("friend");
			$_POST[inputtime]=date("Y-m-d H:i:s");
			$list=$db->data($_POST)->add();
			$this->redirect("Friend/index");
		}else{
			$this->display();
		}
	}
	public function del(){
		$db=M("friend");
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M("friend");
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		$this->redirect("Friend/index");
	}
	public function edit(){
		//绑定标签
		$w="friend";
		$this->assign("w",$w);
		$db=M("friend");
		if(!empty($_POST)){
			if($_FILES['img']['name']!=null){
				$img=$this->_UpImg($_POST['img'],"./UploadFiles/images/");
				$_POST['img'] = $img['savename'];
			}
			$_POST[inputtime]=date("Y-m-d H:i:s");
			$db->data($_POST)->save();
			$this->redirect("Friend/index");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}

}
?>