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
            $sSword = ' AND (title like "%' . $this->db->escape_str($search_word)  . '%" or content like "%' . $this->db->escape_str($search_word) . '%") ';
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
        WHERE board.deleted_flag = 'n'
        ".$sSword."
        ORDER BY board_id 
        DESC " . $limit_query;

//        $sQuery = "
//        SELECT b.*, u.`login_id`
//        FROM board b
//        LEFT JOIN USER u
//        ON b.`user_id` = u.user_id;
//
//        ";

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
        WHERE board_id='" . $this->db->escape_str($id) . "'

        ";
        // 조횟수 증가
        $sql0 = "UPDATE board SET hits = hits + 1 WHERE board_id='" . $this->db->escape_str($id) . "'";
        $this -> db -> query($sql0);

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
            'user_id' => $this->db->escape_str($arrays['user_id']),
            'title' => $this->db->escape_str($arrays['title']),
            'content' => $this->db->escape_str($arrays['content']),
            'created' => date("Y-m-d H:i:s")
        );


        $result = $this->db->insert($arrays['table'], $insert_array);

        return $result;
    }
    /**
     * 게시물 수정
     *
     * @param array $arrays 테이블 명, 게시물 번호, 게시물 제목, 게시물 내용
     * @return boolean 성공 여부
     */
    function modify_board($arrays) {
        $modify_array = array(
            'title' => $this->db->escape_str($arrays['title']),
            'content' => $this->db->escape_str($arrays['content'])
        );

        $where = array(
            'board_id' => $this->db->escape_str($arrays['board_id'])
        );

        $result = $this->db->update($arrays['table'], $modify_array, $where);

        return $result;
    }
    /**
     * 게시물 삭제
     *
     * @param string $table 테이블 명
     * @param string $no 게시물 번호
     * @return boolean 성공 여부
     *
     */
    function delete_content($no) {
        $sql = "UPDATE board SET deleted_flag = 'y' WHERE board_id='" . $this->db->escape_str($no) . "'";
        $query = $this -> db -> query($sql);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }


}
