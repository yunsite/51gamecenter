<?php
// 本文档自动生成，仅供测试运行
class EventAction extends _PublicAction
{
    public function index()
    {		
		//绑定标签
		$w="operate";
		$this->assign("w",$w);	

		$b="event_";
		$this->assign("b",$b);
			
		$db = M("event"); 
 		import("ORG.Util.Page"); 
 		$count = $db->count(); 
 		$Page = new Page($count,10); 
		$Page->setConfig('header','条记录');
 		$show = $Page->show(); 
		$list = $db->where("platform ='$_GET[platform]'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display();
    }
}
?>