<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class GroupModel extends CI_Model {
    function __construct()
    {
        parent::__construct();
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
}