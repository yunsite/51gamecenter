<?php
class LoginAction extends Action
{
	public function login(){
		if($_POST[username]=="admin"){
			if(md5($_POST[password])==md5("admin")){
				$_SESSION[userid]="1";
				$_SESSION[username]="yanglun";
				exit("1");
			}else{
				exit("0");
			}
		}else{
			exit("0");
		}
	}
	public function index(){
		$this->trace('我很丑，但是我很温柔','5211314');
		//halt('我很丑，但是我很温柔');
		$this->display();
	}
	
}
?>