<?php  if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *  게시판 메인 컨트롤러
 */
class Board extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Board_model');
        //$this->load->helper(array('url', 'date'));
    }

    /**
     * 주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
     */
    public function index()
    {
        $this->lists();
    }

    /**
     * 사이트 헤더, 푸터가 자동으로 추가된다.
     */
    public function _remap($method)
    {
        // 헤더 include
        $this->load->view('Header_view');

        if (method_exists($this, $method)) {
            $this->{"{$method}"}();
        }

        // 푸터 include
        $this->load->view('Footer_view');
    }

    /**
     * 목록 불러오기
     */
    public function lists()
    {
        $this->load->library('pagination');

//        $data['list'] = $this->Board_model->get_list();
//        $this->load->view('board/List_view', $data);
        // 페이지 네이션 설정
        $config['base_url'] = '/board/lists/board/page';
        // 페이징 주소
        $config['total_rows'] = $this -> Board_model -> get_list($this -> uri -> segment(3), 'count');
        // 게시물 전체 개수
        $config['per_page'] = 5;
        // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 5;
        // 페이지 번호가 위치한 세그먼트

        // 페이지네이션 초기화
        $this -> pagination -> initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this -> pagination -> create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this -> uri -> segment(5, 1);
        if ($page > 1) {
            $start = (($page / $config['per_page'])) * $config['per_page'];
        } else {
            $start = ($page - 1) * $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['list'] = $this -> Board_model -> get_list($this -> uri -> segment(3), '', $start, $limit);
        $this -> load -> view('board/List_view', $data);



    }

}
