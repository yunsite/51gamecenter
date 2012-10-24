<?php
class KeywordAction extends _PublicAction
{
	private $table='keyword';
    public function index()
    {
		//绑定标签
		$w=$this->table;
		$this->assign("w",$w);
		$num=2;
		if($_GET['platform']==1){
		$num=2;
		}else if($_GET['platform']==0){
		$num=1;
		}
		$b=$this->table."_".$num;
		$this->assign("b",$b);
		
		$db = M($this->table); 
 		import("ORG.Util.Page"); 
 		$count = $db->where("platform ='$_GET[platform]'")->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','条记录');
 		$show = $Page->show(); 
		$list = $db->where("platform ='$_GET[platform]'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }
	public function edit(){
		//绑定标签
		$w=$this->table;
		$this->assign("w",$w);
		$db=M($this->table);
		if(!empty($_POST)){
			unset($_POST['__hash__']);
			if(!$db->data($_POST)->save()){
			echo "test";die;
			}
			header("Location: ?m=".ucfirst($this->table)."&a=index&platform=$_POST[platform]");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
	public function del(){
		$db=M($this->table);
		$sta=$db->where("id = '$_GET[id]'")->delete();
		if($sta){
			exit("1");
		}else{
			exit("0");
		}
	}
	public function dels(){
		$db=M($this->table);
		for($i=0;$i<count($_POST['dels']);$i++){
			$id = $_POST['dels'][$i];
			$db->where("id = '$id'")->delete();
		}
		header("Location: ?m=".ucfirst($this->table)."&a=index&platform=$_GET[platform]");
	}
	public function add(){
		$w=$this->table;
		$this->assign("w",$w);
		$b=$this->table."_3";
		$this->assign("b",$b);
		$db=M($this->table);
		if(!empty($_POST)){
			$db->data($_POST)->add();
			header("Location: ?m=".ucfirst($this->table)."&a=index&platform=$_POST[platform]");
		}else{
			$list=$db->getById("$_GET[id]");
			$this->assign("list",$list);
			$this->display();
		}
	}
}
?>