<?php if (!defined('BASEPATH'))
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


        //     $this -> session -> set_userdata("login_id","advisor1");
//echo $this->session->userdata('login_id');)
//        echo $this->session->userdata('login_id');
//        exit;
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
//        if ($method != 'getCommentList' )  {
//            $this->load->view('Header_view');
//        }
        if (!($this->input->is_ajax_request()))
        {
            $this->load->view('Header_view');
        }

        if (method_exists($this, $method)) {

            $this->{"{$method}"}();
        }

        // 푸터 include
        if (!($this->input->is_ajax_request())) {
            $this->load->view('Footer_view');
        }
    }

    /**
     * 게시물 쓰기
     */
    function write()
    {
        $this->load->helper('alert');
        echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';

        //var_dump($this -> session -> userdata('user_id'));
        if (!empty(@$this->session->userdata('login_id'))) {
            // 폼 검증 라이브러리 로드
            $this->load->library('form_validation');

            // 폼 검증할 필드와 규칙 사전 정의
            $this->form_validation->set_rules('title', '제목', 'required');
            $this->form_validation->set_rules('content', '내용', 'required');


            if ($this->form_validation->run() == TRUE) {
                // 글쓰기 POST 전송 시

                $write_data = array(
                    'table' => 'board',
                    'title' => $this->input->post('title', TRUE),
                    'content' => $this->input->post('content', TRUE),
                    'user_id' => $this->session->userdata('user_id')
                );

                $result = $this->Board_model->insert_board($write_data);

                $nBoardId = $this->db->insert_id();

                $this->file($nBoardId);

                if ($result) {
                    alert("입력되었습니다.", '/board/');



                    exit;
                } else {
                    alert("다시 입력해주세요.", '/board/');
                    exit;
                }
            } else {
                // 쓰기 폼 view 호출
                $this->load->view('board/Write_view');
            }

        } else {
            alert('로그인 후 작성하세요', '/auth/login/');
            exit;
        }

    }

    /**
     * 게시물 파일
     */
   public function file($nBoardId)
    {
        $config['upload_path'] = './data/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 3000;


        $this->load->library('upload', $config);
        $this->load->library('MY_Upload', $config);


        echo $this->upload->do_multi_upload('files');  // 파일/사진 여러개 업로드 메소드
        echo 'hi';
        foreach ($this->upload->get_multi_upload_data() as $mulit_data) {
                    // 파일/사진 세부 데이터들
//            $_FILES['images[]']['name']= $files['name'][$key];
//            $_FILES['images[]']['type']= $files['type'][$key];
//            $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
//            $_FILES['images[]']['error']= $files['error'][$key];
//            $_FILES['images[]']['size']= $files['size'][$key];
            $file_data = array(
                'board_id' => $nBoardId,
                'table' => 'file',
                'user_id' => $this->session->userdata('user_id')
//                'file_name' => $mulit_data->
            );
            $this->Board_model->insert_file($file_data);
        }
        exit;
    }
//    function do_upload()
//    {
//        $this->load->library('upload');
//
//        $files = $_FILES;
//        $cpt = count($_FILES['userfile']['name']);
//        for($i=0; $i<5; $i++)
//        {
//            $_FILES['userfile']['name']= $files['userfile']['name'][$i];
//            $_FILES['userfile']['type']= $files['userfile']['type'][$i];
//            $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
//            $_FILES['userfile']['error']= $files['userfile']['error'][$i];
//            $_FILES['userfile']['size']= $files['userfile']['size'][$i];
//
//            $this->upload->initialize($this->set_upload_options());
//            $this->upload->do_upload();
//        }
//    }
//
//    private function set_upload_options()
//    {
//        //upload an image options
//        $config = array();
//        $config['upload_path'] = './data/';
//        $config['allowed_types'] = 'gif|jpg|png';
//        $config['max_size']      = '100';
//        $config['overwrite']     = FALSE;
//
//        return $config;
//    }

    /**
     * 게시물 보기
     */
    function view()
    {
        // 게시판 이름과 게시물 번호에 해당하는 게시물 가져오기
        $this->output->enable_profiler(TRUE);

        $nBoardId = $this->input->post_get('id', true);
        $data['views'] = $this->Board_model->get_view($nBoardId);

        // 게시판 이름과 세미루 번호에 해당하는 댓글 리스트 가져오기
        $data['comment_list'] = $this->Board_model->get_comment($nBoardId);

        // view 호출
        $this->load->view('board/View_view', $data);


    }

    public function getCommentList()
    {
        $nBoardId = $this->input->post('board_id');

        $aViewData['comment_list'] = $this->Board_model->get_comment($nBoardId);
        $aData['sHtml'] = $this->load->view('board/View_comment_list', @$aViewData, true);

        echo json_encode($aData);
    }

    public function ajax_comment_add()
    {
        if (@$this->session->userdata('logged_in') == TRUE) {
            $this->load->model('Board_model');

            $table = 'comment';
            $board_id = $this->input->post('board_id', TRUE);
            $content = $this->input->post('content', TRUE);
            $parent_id = $this->input->post('parent_id', TRUE);
//            var_dump($board_id);
            if ($content != '') {
                if ($parent_id != '') {

                    $write_data = array(
                        "table" => $table,
                        "board_id" => $board_id,
                        "content" => $content,
                        "user_id" => $this->session->userdata('user_id'),
                        "parent_id" => $parent_id

                    );
                } else {
                    $write_data = array(
                        "table" => $table,
                        "board_id" => $board_id,
                        "content" => $content,
                        "user_id" => $this->session->userdata('user_id'),
                        "parent_id" => 0
                    );
                }

                //$aReturnData['result'] = "200";
                //echo json_encode($aReturnData);
                $nBoardId = $this->Board_model->insert_comment($write_data);

                if($nBoardId){

                }else{
                    //글실패시
                    $aReturnData['result'] = "2000";
                    echo json_encode($aReturnData);
                }
                $aReturnData['result'] = "200";
                echo json_encode($aReturnData);

            } else{
                //글내용이 없을경우
                $aReturnData['result'] = "1000";
                echo json_encode($aReturnData);
            }
        }else{
            //로그인필요에러
            $aReturnData['result'] = "9000";
            echo json_encode($aReturnData);
        }

    }

    /**
     * ajax 방식 댓글 삭제
     */
    public function ajax_comment_delete()
    {
        if (@$this->session->userdata('logged_in') == TRUE) {
            $this->load->model('Board_model');

            $table = 'comment';
            $comment_id = $this->input->post("comment_id", TRUE);

            $writer_id = $this->Board_model->writer_check2($comment_id);
//
//           echo   $writer_id -> user_id;
//           exit;
            if ($writer_id->user_id != $this->session->userdata('user_id')) {
                $aReturnData['result'] = "8000";
                echo json_encode($aReturnData);
//                echo "8000";
            } else {
                $result = $this->Board_model->delete_comment($comment_id);

                if ($result) {
                    echo $comment_id;
                } else {
                    $aReturnData['result'] = "2000";
                echo json_encode($aReturnData);
                    //echo "2000"; // 글 실패
                }
            }
        } else {
            $aReturnData['result'] = "9000";
            echo json_encode($aReturnData);
            //echo "9000"; // 로그인 에러
        }




    }

    /**
     * 목록 불러오기
     */
    public function lists()
    {

        $this->output->enable_profiler(TRUE);
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
        $config['base_url'] = '/board/' . $page_url;  // 페이징 주소

        //$search_word = $this->input->get('search_word');
        $config['total_rows'] = $this->Board_model->get_list('board', 'count',                     //$this -> uri -> segment(1);
            '', '', $search_word);  // segment: 1 // 게시물 전체 개수
        $config['per_page'] = 5; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = $uri_segment; // 페이지 번호가 위치한 세그먼트

        // 페이지네이션 초기화
        $config['use_page_numbers'] = TRUE;
        //$config['enable_query_strings'] = true;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->input->get('per_page');
        if ($page > 1) {
            $start = ($page - 1) * $config['per_page'];//(($page / $config['per_page'])) * $config['per_page']*5;
        } else {
            $start = 0;//($page - 1) * $config['per_page'];
        }
        $limit = $config['per_page'];
        //$search_word = $this->input->get('search_word');
        $data['list'] = $this->Board_model->get_list('board', '', $start, $limit,
            $search_word);
        $this->load->view('board/List_view', $data);
    }

    /**
     * url 중 키 값을 구분하여 값을 가져오도록
     *
     * @param Array $url : segment_explode 한 url 값
     * @param String $key :  가져오려는 값의 key
     * @return String $url[$k] : 리턴 값
     */

    function url_explode($url, $key)
    {
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
    function segment_explode($seg)
    {
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

    function modify()
    {
        $this->load->helper('alert');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';


        if (@$this->session->userdata['logged_in'] == TRUE) {
            $write_id = $this->Board_model->writer_check();

            if ($write_id->user_id != $this->session->userdata('user_id')) {
                alert('본인이 작성한 글이 아닙니다.', '/board/view/?id=' . $this->input->get('id') . '&per_page=' . $this->input->get('per_page'));
                exit;
            }

            $this->load->library('form_validation');

            // 폼 검증할 필드와 규칙 사전 정의
            $this->form_validation->set_rules('title', '제목', 'required');
            $this->form_validation->set_rules('content', '내용', 'required');

            if ($this->form_validation->run() == TRUE) {


                if (!$this->input->post('title', TRUE) and !$this->input->post('content', TRUE)) {
                    alert('비정상적인 접근입니다.', '/board/' . '?per_page=' . $this->input->get('per_page', TRUE));
                    exit;
                }

                $modify_data = array(
                    'table' => 'board',
                    'board_id' => $this->input->get('id', TRUE),
                    'title' => $this->input->post('title', TRUE),
                    'content' => $this->input->post('content', TRUE)
                );

                $result = $this->Board_model->modify_board($modify_data);

                if ($result) {
                    alert('수정되었습니다.', '/board/?per_page=' . $this->input->get('per_page', TRUE));
                    exit;
                } else {
                    alert('다시 수정해 주세요.', '/board/view/?id=' . $this->input->get('id', TRUE) . '&per_page=' . $this->input->get('per_page', TRUE));
                    exit;
                }

            } else {
                $data['views'] = $this->Board_model->get_view($this->input->get('id', TRUE));
                $this->load->view('board/Modify_view', $data);
            }
        } else {
            alert('로그인 후 수정하세요', '/Auth/login/');
            exit;
        }


    }

    /**
     * 게시물 삭제
     */

    function delete()
    {
        $this->load->helper('alert');
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        if (@$this->session->userdata('logged_in') == TRUE) {
            $writer_id = $this->Board_model->writer_check();

            if ($writer_id->user_id != $this->session->userdata('user_id')) {
                alert('본인이 작성한 글이 아닙니다.', '/board/view/?id=' . $this->input->get('id', TRUE) . '/&per_page=' . $this->input->get('per_page', TRUE));
                exit;
            }

            $return = $this->Board_model->delete_content($this->input->get('id', TRUE));

            if ($return) {
                alert('삭제되었습니다.', '/board/?per_page=' . $this->input->get('per_page', TRUE));
                exit;
            } else {
                alert('삭제 실패하였습니다.', '/board/view/?id=' . $this->input->get('id', TRUE) . '/&per_page=' . $this->input->get('per_page', TRUE));
                exit;
            }

        } else {
            alert('로그인 후 삭제하세요.', '/Auth/login/');
            exit;
        }
    }
}
