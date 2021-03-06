<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 사용자 인증 컨트롤러
 */

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct(); // 사용자 인증 모델과 폼 헬퍼를 로딩 합니다
        $this -> load -> model('auth_m');
        //CSRF 방지
        $this -> load -> helper('form');
    }

    public function index() {
        $this -> login();
    }

    public function _remap($method) {
        $this -> load -> view('header_v');

        if (method_exists($this, $method)) {
            $this -> {"{$method}"}();
        }

        $this -> load -> view('footer_v');
    }

    /**
     * 로그인 처리
     */
    public function login() {
        // 폼 검증 라이브러리 로드
        $this -> load -> library('form_validation');

        $this -> load -> helper('alert');
        // 폼 검증할 필드와 규칙 사전 정의
        $this -> form_validation -> set_rules('username', '아이디', 'required|alpha_numeric');
        $this -> form_validation -> set_rules('password', '비밀번호', 'required');

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ($this -> form_validation -> run() == TRUE) {
            $auth_data = array(
                'username' => $this -> input -> post('username', TRUE),
                'password' => $this -> input -> post('password', TRUE)
            );

            $result = $this -> auth_m -> login($auth_data);

            if ($result) {
                //세션 생성
                $newdata = array(
                    'username' => $result -> username,
                    'email' => $result -> email,
                    'logged_in' => TRUE
                );

                $this -> session -> set_userdata($newdata);

                alert('로그인 되었습니다.', '/bbs/board/lists/ci_board/page/1');
                exit;
            } else {
                alert('아이디나 비밀번호를 확인해 주세요.', '/bbs/board/lists/ci_board/page/1');
                exit;
            }
        } else {
            $this -> load -> view('auth/login_v');
        }
    }

    public function logout() {
        $this -> load -> helper('alert');

        $this -> session -> sess_destroy();

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        alert('로그아웃 되었습니다.', '/bbs/board/lists/ci_board/page/1');
        exit;

    }

}


