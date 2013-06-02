<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UserModel extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function confirm($uid,$confirmerUID){
		$this->db->where("UID" , $uid);
		$this->db->update("spamuser",Array("Enabled" => true, "ModifyDate" => db_current_date(),"ConfirmerFBUID"=>$confirmerUID));
	}

	function mark_as($uid,$confirmerUID,$read){
		$this->db->where(Array("UID" => $uid , "Enabled" => false));
		$this->db->update("spamuser",
			Array("Read" => $read,
				"ModifyDate" => db_current_date(),"ConfirmerFBUID"=>$confirmerUID)
		);
	}


	function check_reported($uid,$reporteduid){
		$this->db->select("UserID,CreateDate");
		$this->db->where("UID",$uid);
		$this->db->where("ReporterFBUID",$reporteduid);
		$query = $this->db->get("reportuser");

		if ($query->num_rows() <= 0){ //如果查不到資料
			return null;
		}
		return $query->row();
	}

	function find_by_uid($gid){
		$this->db->select("UID,FirstName,LastName,CreateDate,ModifyDate,Enabled");
		$this->db->where("UID",$gid);
		$query = $this->db->get("spamuser");

		if ($query->num_rows() <= 0){ //如果查不到資料
			return null;
		}
		return $query->row();
	}

	function insert_report($data){
		if($data == null){
			//Unexpected Issue
			return -1;
		}
		$data["ModifyDate"] = db_current_date();
		$this->db->insert('reportuser', $data);
		return $this->db->insert_id();
	}
	function insert($data ){
		if($data == null){
			//Unexpected Issue
			return -1;
		}
		$data["ModifyDate"] = db_current_date();
		$this->db->insert('spamuser', $data);
		return $this->db->insert_id();
	}

	function getUIDs(){
		$this->db->select("UID,Name,CreateDate,FriendCount,ModifyDate");
		$this->db->where("Enabled",true);
		$query = $this->db->get("spamuser");
		$items = $query->result();
		return $items;
	}

	function getConfirmingUIDs(){
		$this->db->select("UID,Name,CreateDate,Read,ModifyDate,
			Read,IsBlocked,FriendCount,
			(select count(distinct ReporterFBUID) from reportuser
				where reportuser.UID = spamuser.UID) as RequestCount");

		$this->db->where("Enabled",false);
		$query = $this->db->get("spamuser");
		return $query->result();
	}

//	function stat_day(){
//		$query = $this->db->query("SELECT DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) AS report_date, ".
//			" COUNT( GroupID ) AS group_count FROM  `spamgroup` where CreateDate > '2013/2/18' ".
//			" GROUP BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ".
//			" ORDER BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ASC");
//		$items = $query->result();
//		return $items;
//	}
//
	function stat_day_enabled(){
		$query = $this->db->query("SELECT DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) AS report_date, ".
			" COUNT( UserID ) AS group_count FROM  `spamuser` where Enabled = 1 and  CreateDate > '2013/2/18'".
			" GROUP BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ".
			" ORDER BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ASC");
		$items = $query->result();
		return $items;
	}

	function confirm_avg_date(){
		$query = $this->db->query("SELECT avg(DATEDIFF(ModifyDate,CreateDate)) AS time FROM  `spamuser`".
			" where Enabled = 1 and CreateDate <> ModifyDate ");
		return $query->row()->time;
	}
}