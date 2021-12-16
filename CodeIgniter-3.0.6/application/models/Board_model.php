<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 공통 게시판 모델
 */
class Board_model extends CI_Model
{
    function __construct()
    {
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
    function get_list($table = 'board', $type = '', $offset = '', $limit = '', $search_word =' ')
    {
        $table = 'board';
        $sSword = '';
        if ($search_word != '') {
            // 검색어 있을 경우
//            $sSword = ' WHERE subject like "%' . $search_word . '%" or contents like "%' . $search_word . '%" ';
            $sSword = ' WHERE title like "%' . $search_word . '%" or content like "%' . $search_word . '%" ';

        }


        $limit_query = '';

        if ($limit != '' or $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }
//        echo ($table);exit;
        $sql = "SELECT * FROM ". $table .$sSword. " ORDER BY board_id DESC " . $limit_query;


// $sql = "SELECT b.*, 'u.login_id'
//        FROM 'board b'
//        LEFT JOIN USER u
//        ON b.'user_id' = u.'user_id'
//ORDER BY board_id DESC " . $limit_query;
        $query = $this->db->query($sql);

        if ($type == 'count') {
            $result = $query->num_rows();
        } else {
            $result = $query->result();
        }

        return $result;
    }
    /**
     * 게시물 상세보기 가져오기
     *
     * @param string $table 게시판 테이블
     * @param string $id 게시물 번호
     * @return array
     */
    public function get_view($id) {
        /*
        $sQuery = "
            SELECT
                *
            FROM board
            WHERE board_id = ?
            limit ?, ?
        ";
        $this->db->query($sQuery, array($id, $limit1, $limit2));
        */
        // 조횟수 증가
//        $sql0 = "UPDATE " . $table . " SET hits = hits + 1 WHERE board_id='" . $id . "'";
        $sql0 = "UPDATE board SET hits = hits + 1 WHERE board_id='" . $id . "'";
        $this -> db -> query($sql0);

//        $sql = "SELECT * FROM " . $table . " WHERE board_id = '" . $id . "'";
        $sql = "SELECT * FROM board WHERE board_id = '" . $id . "'";
        $query = $this -> db -> query($sql);

        // 게시물 내용 반환
        $result = $query -> row();

        return $result;

    }


}
