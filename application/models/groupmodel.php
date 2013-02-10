<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class GroupModel extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function getGIDs(){
    	$this->db->select("GID,Name");
    	$query = $this->db->get("spamgroup");
    	$items = $query->result();
    	return $items;
    }
}