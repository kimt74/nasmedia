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
        $this->load->helper(array('url', 'date'));
//        $this -> load -> helper('form');
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
     * 게시물 쓰기
     */
    function write() {
        echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
        $this -> output -> enable_profiler(TRUE);
        if ($_POST) {
            // 글쓰기 POST 전송 시
            $this -> load -> helper('alert');

            // 주소 중에서 page 세그먼트가 있는지 검사하기 위해 주소를 배열로 반환
            $uri_array = $this -> segment_explode($this -> uri -> uri_string());


            if (in_array('page', $uri_array)) {
                $pages = urldecode($this -> url_explode($uri_array, 'page'));
            } else {
                $pages = 1;
            }


            if (!$this -> input -> post('title', TRUE) AND !$this -> input -> post('content', TRUE)) {
                // 글 내용이 없을 경우, 프로그램 단에서 한 번 더 체크
                alert('비정상적인 접근입니다.', '/board/');
                exit ;
            }

            // var_dump($_POST);
            $write_data = array(
                'title' => $this -> input -> post('title', TRUE),
                'content' => $this -> input -> post('content', TRUE),
                'table' => 'board'
            );

            $result = $this -> Board_model -> insert_board($write_data);

            if ($result) {
//                alert("입력되었습니다.",'/bbs/board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                alert("입력되었습니다.",'/board/');
                exit;
            } else {
//                alert("다시 입력해주세요.",'/bbs/board/lists/'.$this->uri->segment(3).'/page/'.$pages);
                alert("다시 입력해주세요.",'/board/');
                exit;
            }
        } else {
            // 쓰기 폼 view 호출
            $this->load->view('board/Write_view');
        }
    }




    /**
     * 게시물 보기
     */
    function view() {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기
        $this -> output -> enable_profiler(TRUE);

        $nBoardId =  $this->input->post_get('id', true);
        $data['views'] = $this -> Board_model -> get_view($nBoardId);
        // view 호출
        $this -> load -> view('board/View_view', $data);
    }

    /**
     * 목록 불러오기
     */
    public function lists()
    {

        $this -> output -> enable_profiler(TRUE);
        // 검색어 초기화
        $search_word = $page_url = '';
        $search_word = $this->input->get('search_word', true);
        $uri_segment = 5;

        if ($search_word) {
            // 주소에 검색어가 있을 경우 처리

            // 페이지네이션 용 주소
            $page_url = '/?search_word=' . $search_word;
        }


        // 페이지네이션 라이브러리 로딩
        $this->load->library('pagination');


        // 페이지 네이션 설정
        $config['base_url'] = '/board/'. $page_url;  // 페이징 주소

        //$search_word = $this->input->get('search_word');
        $config['total_rows'] = $this -> Board_model -> get_list('board', 'count',                     //$this -> uri -> segment(1);
            '', '', $search_word);  // segment: 1 // 게시물 전체 개수
        $config['per_page'] = 5; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = $uri_segment; // 페이지 번호가 위치한 세그먼트

        // 페이지네이션 초기화
        $config['use_page_numbers'] = TRUE;
        //$config['enable_query_strings'] = true;
        $config['page_query_string'] = TRUE;
        $this -> pagination -> initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this -> pagination -> create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this -> input->get('per_page');
        if ($page > 1) {
            $start = ($page - 1) * $config['per_page'];//(($page / $config['per_page'])) * $config['per_page']*5;
        } else {
            $start = 0;//($page - 1) * $config['per_page'];
        }
        $limit = $config['per_page'];
        //$search_word = $this->input->get('search_word');
        $data['list'] = $this -> Board_model -> get_list('board', '', $start, $limit,
        $search_word);
        $this -> load -> view('board/List_view', $data);
    }
    /**
     * url 중 키 값을 구분하여 값을 가져오도록
     *
     * @param Array $url : segment_explode 한 url 값
     * @param String $key :  가져오려는 값의 key
     * @return String $url[$k] : 리턴 값
     */

    function url_explode($url, $key) {
        $cnt = count($url);

        for ($i = 0; $cnt > $i; $i++) {
            if ($url[$i] == $key) {
                $k = $i + 1;
                return $url[$k];
            }
        }
    }

    /**
     * HTTP의 URL을 "/"를 Delimiter로 사용하여 배열로 바꿔 리턴한다.
     *
     * @param String 대상이 되는 문자열
     * @return string[]
     */
    function segment_explode($seg) {
        // 세그먼트 앞 뒤 "/" 제거 후 uri를 배열로 반환

        $len = strlen($seg);

        if (substr($seg, 0, 1) == '/') {
            $seg = substr($seg, 1, $len);
        }

        $len = strlen($seg);

        if (substr($seg, -1) == '/') {
            $seg = substr($seg, 0, $len - 1);
        }

        $seg_exp = explode("/", $seg);
        return $seg_exp;
    }

    function modify() {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if ( $_POST ) {
            $this -> load -> helper('alert');

            $uri_array = $this->segment_explode($this->uri->uri_string());

            if ( in_array('page', $uri_array)) {
                $pages = urldecode($this->url_explode($uri_array, 'page'));
            } else {
                $pages = 1;
            }

            if ( !$this->input->post('title', TRUE) AND !$this->input->post('content', TRUE)) {
                alert('비정상적인 접근입니다.', '/board/');
                exit;
            }


            $modify_data = array(
                'table' => 'board',
                'board_id' => $this->input->get('id'),
                'title' => $this->input->post('title', TRUE),
                'content' => $this->input->post('content', TRUE)
            );

            $result = $this->Board_model->modify_board($modify_data);

            if ( $result ) {
                alert('수정되었습니다.', '/board/');
                exit;
            } else {
                alert('다시 수정해 주세요.', '/board/view?id='.$this->input->get('id'));
                exit;
            }
        } else {
            $data['views'] = $this->Board_model->get_view($this->input->get('id'));
            $this->load->view('board/Modify_view', $data);
        }
    }

    /**
     * 게시물 삭제
     */

    function delete() {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        $this->load->helper('alert');

        $return = $this->Board_model->delete_content($this->input->get('id'));

        if ( $return ) {
            alert('삭제되었습니다.', '/board/');
            exit;
        } else {
            alert('삭제 실패하였습니다.', '/board/view?id='.$this->input->get('id'));
            exit;
        }

    }
}