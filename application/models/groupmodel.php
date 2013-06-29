<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class GroupModel extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	function confirm($gid,$confirmerUID){
		$this->db->where("GID" , $gid);
		$this->db->update("spamgroup",Array("Enabled" => true, "ModifyDate" => db_current_date(),"ConfirmerFBUID"=>$confirmerUID));
	}

	function mark_as($gid,$confirmerUID,$read){
		$this->db->where(Array("GID" => $gid , "Enabled" => false));
		$this->db->update("spamgroup",
			Array("Read" => $read,
				"ModifyDate" => db_current_date(),"ConfirmerFBUID"=>$confirmerUID)
		);
	}

	function find_by_gid($gid){
		$this->db->select("GID,Name,CreateDate,ModifyDate,Enabled");
		$this->db->where("GID",$gid);
		$query = $this->db->get("spamgroup");

		if ($query->num_rows() <= 0){ //如果查不到資料
			return null;
		}
		return $query->row();
	}

	function check_reported($gid,$reporteduid){
		$this->db->select("GroupID,CreateDate");
		$this->db->where("GID",$gid);
		$this->db->where("ReporterFBUID",$reporteduid);
		$query = $this->db->get("reportgroup");

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
		$this->db->insert('reportgroup', $data);
		return $this->db->insert_id();
	}

	function insert($data ){
		if($data == null){
			//Unexpected Issue
			return -1;
		}
		$data["ModifyDate"] = db_current_date();
		$this->db->insert('spamgroup', $data);
		return $this->db->insert_id();
	}

	function getGIDs(){
		$this->db->select("GID,Name,CreateDate,ModifyDate");
		$this->db->where("Enabled",true);
		$query = $this->db->get("spamgroup");
		$items = $query->result();
		return $items;
	}

	function getConfirmingGIDs(){
		$this->db->select("GID,Name,GroupCreator,GroupCreatorName,CreateDate,Type,Read,ModifyDate,(select count(distinct ReporterFBUID) from reportgroup where reportgroup.GID = spamgroup.GID) as RequestCount");
		$this->db->where("Enabled",false);
		$this->db->order_by("Read","desc");
		$this->db->order_by("RequestCount","desc");
		$this->db->order_by("CreateDate","asc");
		$query = $this->db->get("spamgroup");
		$items = $query->result();
		return $items;
	}

	function stat_day(){
		$query = $this->db->query("SELECT DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) AS report_date, ".
			" COUNT( GroupID ) AS group_count FROM  `spamgroup` where CreateDate > '2013/2/18' ".
			" GROUP BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ".
			" ORDER BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ASC");
		$items = $query->result();
		return $items;
	}

	function stat_day_enabled(){
		$query = $this->db->query("SELECT DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) AS report_date, ".
			" COUNT( GroupID ) AS group_count FROM  `spamgroup` where Enabled = 1 and  CreateDate > '2013/2/18'".
			" GROUP BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ".
			" ORDER BY DATE_FORMAT(  `CreateDate` ,  '%Y/%m/%d' ) ASC");
		$items = $query->result();
		return $items;
	}

	function confirm_avg_date(){
		$query = $this->db->query("SELECT avg(DATEDIFF(ModifyDate,CreateDate)) AS time FROM  `spamgroup`".
			" where Enabled = 1 and CreateDate <> ModifyDate ");
		return $query->row()->time;
	}
}