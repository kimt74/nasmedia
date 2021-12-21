<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Auth_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * 아이디 비밀번호 체크
     *
     * @param array $auth 폼 전송받은 아이디, 비밀번호
     * @return array
     */
    function login($auth) {
        $sql = "SELECT login_id, email FROM user WHERE login_id = '" . $auth['login_id'] . "' AND pw = '" . $auth['pw'] . "' ";

        $query = $this -> db -> query($sql);

        if ($query -> num_rows() > 0) {
            return $query -> row();
        } else {
            return FALSE;
        }
    }

}
