<?php
class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function gets()
    {
        return $this->db->query("SELECT * FROM user")->result();
    }

    function get($option)
    {
        $result = $this->db->get_where('login_id', array('email'=>$option['email']))->row();
        var_dump($this->db->last_query());
        return $result;
    }

    function add($option)
    {
        $this->db->set('email', $option['email']);
        $this->db->set('pw', $option['pw']);
        $this->db->set('created', 'NOW()', false);
        $this->db->insert('login_id');
        $result = $this->db->insert_id();
        return $result;
    }
}