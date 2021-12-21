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

                $this -> session -> set_userdata('login_id',$result -> login_id );
print_r($this -> session);
echo $this->session->userdata('login_id');
                echo $this->session->userdata('login_id');
               echo "<script>document.location.href='/board';</script>";
             //        alert('로그인 되었습니다.', '/board/?per_page=1');
                exit;
            } else {
                alert('아이디나 비밀번호를 확인해 주세요.', '/board/?per_page=1');
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
        alert('로그아웃 되었습니다.', '/board/?per_page=1');
        exit;

    }

    function register(){
        //$this->_head();
        $this -> load -> view('Header_view');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', '이메일 주소', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('login_id', '로그인아이디', 'required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('pw', '비밀번호', 'required|min_length[6]|max_length[30]|matches[re_password]');
        $this->form_validation->set_rules('re_pw', '비밀번호 확인', 'required');

        if($this->form_validation->run() === false){
            $this->load->view('register');
        } else {
            if(!function_exists('password_hash')){
                $this->load->helper('password');
            }
            $hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

            $this->load->model('user_model');
            $this->user_model->add(array(
                'email'=>$this->input->post('email'),
                'password'=>$hash,
                'nickname'=>$this->input->post('nickname')
            ));

            $this->session->set_flashdata('message', '회원가입에 성공했습니다.');
            $this->load->helper('url');
            redirect('/');
        }


        $this->_footer();
    }



}


