<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	public function _remap($method, $params = array())
	{
		if (!method_exists($this, $method))
		{
			show_404();
			return null;
		}
		$this->load->database();
		return call_user_func_array(array($this, $method), $params);

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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */