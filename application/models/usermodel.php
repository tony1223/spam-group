<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UserModel extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function insert($account,$password){
        $this->db->insert("user", 
            Array(
            "account" =>  $account,
            "password" => $password
        ));     
    }    

    function checkUserExist($account){
        $this->db->select("COUNT(*) AS users");
        $this->db->from("user");
        $this->db->where("account", $account);
        $query = $this->db->get(); 

        return $query->row()->users > 0 ;
    }

    public function getUser($account,$password){
        $this->db->select("*");
        $query = $this->db->get_where("user",Array("account" => $account, "password" => $password ));

        if ($query->num_rows() > 0){ //如果數量大於0
            return $query->row();  //回傳第一筆
        }else{
            return null;
        }
    }

    public function getUserByAccount($account){
        $this->db->select("*");
        $query = $this->db->get_where("user",Array("account" => $account));

        if ($query->num_rows() <= 0){ //如果找不到
            return null;
        }

        return $query->row();  //回傳第一筆
    }
}