<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 사용자 인증 컨트롤러
 */

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct(); // 사용자 인증 모델과 폼 헬퍼를 로딩 합니다
        $this -> load -> model('Auth_model');
        //CSRF 방지
        $this -> load -> helper('form');
    }

    public function index() {
        $this -> login();
    }

    public function _remap($method) {
        $this -> load -> view('Header_view');

        if (method_exists($this, $method)) {
            $this -> {"{$method}"}();
        }

        $this -> load -> view('Footer_view');
    }

    /**
     * 로그인 처리
     */
    public function login() {
        // 폼 검증 라이브러리 로드
        $this -> load -> library('form_validation');

        $this -> load -> helper('alert');
        // 폼 검증할 필드와 규칙 사전 정의
        $this -> form_validation -> set_rules('login_id', '아이디', 'required|alpha_numeric'); //아이디는 필수 영문과 숫자
        $this -> form_validation -> set_rules('pw', '비밀번호', 'required');

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ($this -> form_validation -> run() == TRUE) {
            $auth_data = array(
                'login_id' => $this -> input -> post('login_id', TRUE),
                'pw' => $this -> input -> post('pw', TRUE)
            );

            $result = $this -> Auth_model -> login($auth_data);

            if ($result) {
                //세션 생성
                $newdata = array(
                    'login_id' => $result -> login_id,
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
            $this -> load -> view('auth/Login_view');
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


