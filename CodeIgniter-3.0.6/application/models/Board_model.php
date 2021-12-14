<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 공통 게시판 모델
 */

class Board_model extends CI_Model {
    function __construct() {
        parent::__construct();
        //load db?
    }

//    function get_list($table = 'board') {
//        $sql = "SELECT * FROM ".$table." ORDER BY board_id DESC";
//        $query = $this->db->query($sql);
//        $result = $query->result();
//        // $result = $query->result_array();
//
//        return $result;
//    }
    function get_list($table = 'board', $type = '', $offset = '', $limit = '') {
        $limit_query = '';

        if ($limit != '' OR $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }

        $sql = "SELECT * FROM " . $table . " ORDER BY board_id DESC ". $limit_query;
        $query = $this -> db -> query($sql);

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result();
        }

        return $result;
    }
}
