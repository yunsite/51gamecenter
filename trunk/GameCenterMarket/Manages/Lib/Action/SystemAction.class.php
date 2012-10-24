<?php
class SystemAction extends _PublicAction
{
	public function edit(){
		$db=M("system");
		if(!empty($_POST['id'])){
			$db->data($_POST)->save();
			$this->redirect("Index/index");
		}else{
			unset($_POST['id']);
			$db->data($_POST)->add();
			$this->redirect("Index/index");
			//$this->display("Index/index");
		}
	}
	public function notice(){
		//绑定标签
		$w="sys";
		$this->assign("w",$w);
		$b="sys_1";
		$this->assign("b",$b);
		
		$db=M("notice");
		if(!empty($_POST)){
			$k = $db->where("id = 1")->data($_POST)->save();
			
			if($k){
				exit("0");
			}else{
				exit("1");
			}
		}else{
			$list = $db -> find();
			$this->assign("list",$list);
			$this->display();
		}
	}
}
?>