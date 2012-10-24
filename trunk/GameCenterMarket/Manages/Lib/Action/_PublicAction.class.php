<?php
class _PublicAction extends Action
{
	public function _initialize(){
		header("Content-Type:text/html; charset=utf-8");
		if($_SESSION[userid]==null){
			//$this->redirect("?m=Login&a=index");
			//die;
			header("Location: ?m=Login&a=index");
		}
	}
	/**
	*传入图片，传入URL 上传图片
	*thumb = true 生成缩略图 
	*默认生成缩略图 为t_开头
	*宽度
	*高度
	*/
	public function _UpImg($img,$url,$thumb=true,$width=300,$height=200){
		import("ORG.Net.UploadFile");	
		$upload = new UploadFile();	
		$upload->maxSize  = 71457280 ;
		$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg','apk');
		if($thumb==true){
			$upload->thumb = true;
		}else{
			$upload->thumb = false;
		}
		$upload->saveRule = md5(time());
		$upload->thumbMaxWidth = $width;
		$upload->thumbMaxHeight = $height;
		$upload->thumbPrefix = "t_";
		$upload->uploadReplace = true;
		$upload->thumbRemoveOrigin = false;
		$upload->thumbPath = $url;
	    $upload->savePath =  $url; 	
		if(!$upload->upload()) {
			echo $upload->getErrorMsg();
		}else{
			$info =  $upload->getUploadFileInfo();
		}
		return $info[0];
	}
	//上传一个文件到DFS
	public function upload_file($file){
		$tracker = fastdfs_tracker_get_connection();
		if (!fastdfs_active_test($tracker))
		{
			error_log("errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());
			exit(1);
		}
		$storage = fastdfs_tracker_query_storage_store();
		if (!$storage)
		{
			error_log("errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());
			exit(1);
		}
		$file_info = fastdfs_storage_upload_by_filename($file, null, array(), null, $tracker, $storage);
		return $file_info;
	}
	//删除一个文件
	public function delete_file($file_name)
	{
		return fastdfs_storage_delete_file("51", $file_name);
	}
}
?>