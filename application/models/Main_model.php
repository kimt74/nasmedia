<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_model {

    function __construct(){
        parent::__construct();
        //위에서 설정한 /application/config/database.php 파일에서 $db['test'] 설정값을 불러오겠다는 뜻입니다.
        $this->myboard = $this->load->database('myboard', TRUE);
    }

    function myboard() {
        //query 함수는 DB에 쿼리를 요청하는 것이며, 이 과정에서 오류가 뜨면 오류메시지가 나옵니다.
        $this->myboard->query("SELECT * FROM board");
        echo "오류 메시지가 안 뜬다면 잘 되는 겁니다";
    }
}