<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * AJAX 처리 컨트롤러
 */
class Ajax_board extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * AJAX 테스트
     */
    public function test()
    {
        $this->load->view('ajax/test_v');
    }

    public function ajax_action()
    {
        echo '<meta http-equiv="Content-Type" content="test/html; charset=utf-8" />';

        $name = $this->input->post("name");

        echo $name . "님 반갑습니다 !";
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
                if($parent_id != '') {

                    $write_data = array(
                        "table" => $table,
                        "board_id" => $board_id,
                        "content" => $content,
                        "user_id" => $this->session->userdata('user_id'),
                        "parent_id" => $parent_id

                    );

                }
                else {
                    $write_data = array(
                        "table" => $table,
                        "board_id" => $board_id,
                        "content" => $content,
                        "user_id" => $this->session->userdata('user_id'),
                        "parent_id" => 0
                    );
                }

                $nBoardId = $this->Board_model->insert_comment($write_data);

                $aReturnData['result'] = "200";
                echo json_encode($aReturnData);

                if ($nBoardId) {

                    $aComment = $this->Board_model->get_comment($board_id);;
                    $aCommentReply = $this->Board_model->get_comment_reply($parent_id);;
                    $viewData = array();
                    $sHtml = $this->load->view("board/comment_list", $viewData)
                    ?>
                    <table cellspacing="1" cellpadding="1" class="table table-striped">
                        <tbody>
                        <?php
                        foreach ($aComment as $lt) {
                            ?>
                            <tr id="row_num_<?php echo $lt->comment_id; ?>" comment_id="<?=$lt->comment_id;?>">
                                <th scope="row">
                                    <?php echo $lt->login_id; ?>
                                </th>

                                <td><?php echo $lt->content; ?>

                                </td>

                                <td>
                                    <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->created)); ?>">
                                        <?php echo $lt->created; ?>
                                    </time>
                                </td>
                                <td>
                                    <a href="#" id="comment_delete" comment_id="<?=$lt->comment_id;?>">
                                        <i class="icon-trash"></i>[삭제]
                                    </a>
                                    <a href="#" id="comment_reply" comment_reply_id="<?=$lt->comment_id;?>">
                                        <i class="icon-trash"></i>[대댓글달기]
                                    </a>
                                </td>
                            </tr>


                            <tr>
                               <td style="text-indent: 50px" id="comment_reply_view">

                               </td>

                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>

                    </table>
                    <?php
                } else {
                    // 글 실패시
                    echo "2000";
                }
            } else {
                // 글 내용이 없을 경우
                echo "1000";
            }
        } else {
            // 로그인 필요 에러
            echo "9000";
        }
    }

    /**
     * ajax 방식 댓글 삭제
     */
    public function ajax_comment_delete() {
        if ( @$this -> session -> userdata('logged_in') == TRUE) {
            $this -> load -> model('Board_model');

            $table = 'comment';
            $comment_id = $this -> input -> post("comment_id", TRUE);

            $writer_id = $this -> Board_model -> writer_check2($comment_id);
//
//           echo   $writer_id -> user_id;
//           exit;
            if ( $writer_id -> user_id != $this -> session -> userdata('user_id')) {
                echo "8000";
            } else {
                $result = $this -> Board_model -> delete_comment($comment_id);

                if ($result) {
                    echo $comment_id;
                } else {
                    echo "2000"; // 글 실패
                }
            }
        } else {
            echo "9000"; // 로그인 에러
        }
    }



}
