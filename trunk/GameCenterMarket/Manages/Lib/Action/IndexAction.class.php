<?php
// 本文档自动生成，仅供测试运行
class IndexAction extends _PublicAction
{
    public function index()
    {
		//绑定标签
		$w="index_1";
		$this->assign("w",$w);
		
		//获取博客内容信息
		$db=M("pa");
		$wz = $db->count();//文章数量
		$this->assign("wz",$wz);
		$db2=M("category");
		$ty = $db2->select();	//获取分类信息
		$this->assign("ty",$ty);
        $this->display();
    }
	public function ex(){
		$_SESSION['users'] = "";
		$this->redirect("Login/index");
	}
	public function delCache(){
		delCache();
		echo "<script>alert('清除成功！');</script>";
		$this->redirect("Index/index");
	}
}
?>