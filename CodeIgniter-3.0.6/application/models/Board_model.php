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
            $sSword = ' WHERE title like "%' . $this->db->escape_str($search_word)  . '%" or content like "%' . $this->db->escape_str($search_word) . '%" ';
        }


        $limit_query = '';

        if ($limit != '' or $offset != '') {
            // 페이징이 있을 경우 처리
            $limit_query = ' LIMIT ' . $offset . ', ' . $limit;
        }
//        echo ($table);exit;
//        $sql = "SELECT * FROM ". $table .$sSword. " ORDER BY board_id DESC " . $limit_query;




        $sql = "
        SELECT * 
        FROM ". $table . " 
        LEFT JOIN user
        ON board.user_id = user.user_id
        ".$sSword."
        ORDER BY board_id 
        DESC " . $limit_query;

        $sQuery = "
        SELECT b.*, u.`login_id`
        FROM board b
        LEFT JOIN USER u
        ON b.`user_id` = u.user_id;

        ";



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
     *
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
        $sQuery = "
        SELECT b.*, u.`login_id`
        FROM board b
        LEFT JOIN USER u
        ON b.`user_id` = u.user_id
        WHERE board_id='" . $id . "'

        ";
        // 조횟수 증가
//        $sql0 = "UPDATE " . $table . " SET hits = hits + 1 WHERE board_id='" . $id . "'";
        $sql0 = "UPDATE board SET hits = hits + 1 WHERE board_id='" . $id . "'";
        $this -> db -> query($sql0);

//        $sql = "SELECT * FROM " . $table . " WHERE board_id = '" . $id . "'";
//        $sql = "SELECT * FROM board WHERE board_id = '" . $id . "'";
        $query = $this -> db -> query($sQuery);

        // 게시물 내용 반환
        $result = $query -> row();

        return $result;

    }

    /**
     * 게시물 입력
     *
     * @param array $arrays 테이블 명, 게시물 제목, 게시물 내용 1차 배열
     * @return boolean 입력 성공여부
     */
    function insert_board($arrays) {
        $insert_array = array(
            'board_pid' => 0,
            'user_id' => 'advisor',
            'user_name' => 'palpit',
            'subject' => $arrays['subject'],
            'contents' => $arrays['contents'],
            'reg_date' => date("Y-m-d H:i:s")
        );

        $result = $this->db->insert($arrays['table'], $insert_array);

        return $result;
    }


}
