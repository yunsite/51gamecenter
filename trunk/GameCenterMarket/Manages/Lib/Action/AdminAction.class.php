<?php


// 本文档自动生成，仅供测试运行
class AdminAction extends _PublicAction {
	public function channel() {
		$db = M("channelinfo");
		import("ORG.Util.Page");
		$count = $db->count();
		$Page = new Page($count, 10);
		$Page->setConfig('header', '条渠道');
		$show = $Page->show();
		$list = $db->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function app() {
		$db = M("appinfo");
		import("ORG.Util.Page");
		$count = $db->count();
		$Page = new Page($count, 10);
		$Page->setConfig('header', '条应用');
		$show = $Page->show();
		$list = $db->order('appid desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function mobile() {
		$db = M("mobileloginfo");
		import("ORG.Util.Page");
		$count = $db->count();
		$Page = new Page($count, 10);
		$Page->setConfig('header', '条应用');
		$show = $Page->show();
		$list = $db->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
//		echo $db->getLastSql();
		$this->assign('list', $list);
		$this->assign('page', $show);
//		var_dump($list);
		$this->display();
	}
	
	
}
?>