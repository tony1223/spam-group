<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends MY_Controller {
	/**
	 *
	 * @var GroupModel
	 */
	var $GroupModel; //GroupModel
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$gids = $this->GroupModel->getGIDs();
		$this->load->view('welcome_message',
			Array(
				"pageTitle" => "Facebook 廣告社團檢查器",
				"fbgids" => $gids,
				"selector" => "check"
			)
		);
	}

	public function groups($type="web"){
		$gids = $this->GroupModel->getGIDs();

		if($type == "json"){
			echo json_encode($gids);
			return true;
		}
		if($type == "jsonp"){
			$jsonp = $this->input->get("jsonp");
			if(!empty($jsonp)){
				echo $jsonp."(".json_encode($gids).")";
			}else{
				echo json_encode($gids);
			}
			return true;
		}


		$this->load->view('group_list',
			Array(
				"pageTitle" => "已認定 Facebook 廣告社團列表",
				"fbgids" => $gids,
				"selector" => "group"
			)
		);
	}

	public function confirming($type="web"){
		$gids = $this->GroupModel->getConfirmingGIDs();

		$this->load->view('group_list',
			Array(
				"pageTitle" => "審核中 Facebook 廣告社團列表",
				"fbgids" => $gids,
				"selector" => "group"
			)
		);
	}

	public function report(){
		$gurl = $this->input->get("gurl");

		$gids = $this->GroupModel->getGIDs();
		$this->load->view('report',
			Array(
				"pageTitle" => "回報 Facebook 廣告社團",
				"fbgids" => $gids,
				"selector" => "report",
				"gurl" => $gurl
			)
		);
	}

	public function js_report_gid(){
		$gid = $this->input->get("gid");
		$group = $this->GroupModel->find_by_gid($gid);
		echo json_encode($group);
	}

	public function js_report_group(){
		$gid = $this->input->post("gid");
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

		if($groupid == -1){
			echo json_encode(Array("IsSuccess" => false, "ErrorCode" => 2 , "ErrorMessage" => "新增群組時發生意外錯誤" ));
			return false;
		}
		echo json_encode(Array("IsSuccess" => true, "Data" => $groupid));
	}
	public function js_insert_group(){
//		die('{"IsSuccess":true,"Data":119}');

		$gid = $this->input->post("gid");
		$name = $this->input->post("name");
		$uid = $this->input->post("uid");
		$uname = $this->input->post("uname");
		$privacy = $this->input->post("privacy");

		$groupid = $this->GroupModel->insert(
			Array(
				"Name" => $name ,
				"GID" => $gid,
				"Type" => $privacy,
				"Enabled" => false,
				"ReporterFBUID" => $uid,
				"Reporter" => $uname
		));

		if($groupid == -1){
			echo json_encode(Array("IsSuccess" => false, "ErrorCode" => 2 , "ErrorMessage" => "新增群組時發生意外錯誤" ));
			return false;
		}
		echo json_encode(Array("IsSuccess" => true, "Data" => $groupid));
		//ReporterFBUID
	}

	public function privacy(){
		echo "本應用程式將取得您的社團清單與您的朋友的社團清單，以為您檢查是否有加入惡意社團清單。";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */