<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	public function _remap($method, $params = array())
	{
		if (!method_exists($this, $method))
		{
			show_404();
			return null;
		}

		if( $method == "users" ){
			return call_user_func_array(array($this, $method), $params);
		}else{
			$this->load->database();
			return call_user_func_array(array($this, $method), $params);
		}

	}

	private function getUids($loaddb = false){
		$this->load->driver('cache');
		if (!$this->cache->file->is_supported()){
			if($loaddb){
				$this->load->database();
			}
			return $this->UserModel->getUIDs();
		}
		$CACHE_ID = "Uids";
		$data = $this->cache->file->get($CACHE_ID);
		if($data != false){
			return $data;
		}
		if($loaddb){
			$this->load->database();
		}
		$result = $this->UserModel->getUIDs();
		$this->cache->file->save($CACHE_ID, $result, 600);
 		return $result;
	}

	public function users($type="web"){
		$uids = $this->getUids(true);

		if($type == "json"){
			echo json_encode($uids);
			return true;
		}else if($type == "jsonp"){
			$jsonp = $this->input->get("jsonp");
			if(!empty($jsonp)){
				echo htmlspecialchars($jsonp)."(".json_encode($uids).")";
			}else{
				echo json_encode($uids);
			}
			return true;
		}


		$this->load->view('user_list',
			Array(
				"pageTitle" => "已認定 Facebook 廣告使用者列表",
				"fbuids" => $uids,
				"selector" => "user"
			)
		);
	}

	public function js_report_uid(){
		$uid = $this->input->get("uid");
		$user = $this->UserModel->find_by_uid($uid);
		echo json_encode($user);
	}

	public function js_insert_user(){
//		die('{"IsSuccess":true,"Data":119}');

		$uid = $this->input->post("uid");
		$name = $this->input->post("last_name")." ".$this->input->post("first_name");
		$first_name = $this->input->post("first_name");
		$last_name = $this->input->post("last_name");
		$is_blocked = $this->input->post("is_blocked");
		$friend_count = $this->input->post("friend_count");
		$friend_request_count  = $this->input->post("friend_request_count");

		$report_uid = $this->input->post("report_uid");
		$report_uname = $this->input->post("report_uname");

		//uid,first_name,last_name,is_blocked,friend_count,friend_request_count
		//scapeHTML(group.creator)+"'>"+escapeHTML(group.creatorName)+"</a>")

		$userid = $this->UserModel->insert(
			Array(
				"Name" => $name ,
				"UID" => $uid,
				"Enabled" => false,
				"ReporterFBUID" => $report_uid,
				"Reporter" => $report_uname,
				"IsBlocked" => $is_blocked,
				"FriendCount" => $friend_count,
				"WaitingFriendCount" => $friend_request_count,
				"FirstName" => $first_name,
				"LastName" => $last_name
		));

		if($userid == -1){
			echo json_encode(Array("IsSuccess" => false, "ErrorCode" => 2 , "ErrorMessage" => "新增使用者時發生意外錯誤" ));
			return false;
		}
		echo json_encode(Array("IsSuccess" => true, "Data" => $userid));
		//ReporterFBUID
	}


	public function js_confirming(){
		if(!isset($_SESSION["admin"])){
			echo json_encode(Array("IsSuccess" => false,"ErrorMessage" => "Not login yet"));
			return false;
		}

		$uid = $this->input->post("uid");

		$userid = $this->UserModel->confirm($uid,$_SESSION["admin"]);
		echo json_encode(Array("IsSuccess" => true));
	}

	public function js_mark_as_read(){
		if(!isset($_SESSION["admin"])){
			echo json_encode(Array("IsSuccess" => false,"ErrorMessage" => "Not login yet"));
			return false;
		}
		$uid = $this->input->post("uid");
		$read = $this->input->post("read");
		if($read == "1"){
			$this->UserModel->mark_as($uid, $_SESSION["admin"] , false);
		}else{
			$this->UserModel->mark_as($uid, $_SESSION["admin"] , true);
		}
		echo json_encode(Array("IsSuccess" => true ,
			"Data" =>
				Array("Status" => ($read =="1" ? "標為已讀" :"標為未讀" ),"Read" => ($read =="1" ? "0":"1" ) )

		));
	}


	public function js_report_user(){
		$gid = $this->input->post("uid");
		$name = $this->input->post("name");
		$uid = $this->input->post("uid");
		$uname = $this->input->post("uname");
		$privacy = $this->input->post("privacy");

		$groupid = $this->GroupModel->insert_report(
			Array(
				"Name" => $name ,
				"GID" => $gid,
				"Type" => $privacy,
				"ReporterFBUID" => $uid,
				"Reporter" => $uname
		));

		//#14 mark as unread after user +1
		$this->GroupModel->mark_as($gid, $uid, false);

		if($groupid == -1){
			echo json_encode(Array("IsSuccess" => false, "ErrorCode" => 2 , "ErrorMessage" => "新增社團時發生意外錯誤" ));
			return false;
		}
		echo json_encode(Array("IsSuccess" => true, "Data" => $groupid));
	}


	public function confirming($type="web"){
		$uids = $this->UserModel->getConfirmingUIDs();

		$this->load->view('user_confirm_list',
			Array(
				"pageTitle" => "審核中 Facebook 廣告使用者列表",
				"fbuids" => $uids,
				"selector" => "group"
			)
		);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */