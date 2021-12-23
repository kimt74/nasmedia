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
//            var_dump($board_id);
            if ($content != '') {
                $write_data = array(
                    "table" => $table,
                    "board_id" => $board_id,
                    "content" => $content,
                    "user_id" => $this->session->userdata('user_id')
                );


                $result = $this->Board_model->insert_comment($write_data);

                if ($result) {
                    $sql = "
                                  SELECT
                                  comment.content,
                                  comment.created,
                                  user.`login_id`,
                                  board_id,
                                  comment_id,
                                  user.`user_id`,
                                  parent_id,
                                  comment.`deleted_flag` 
                                  FROM
                                  " . $table . " 
                                  LEFT JOIN USER
                                  ON comment.user_id = user.user_id 
                                  WHERE board_id = '" . $board_id . "' 
                                  ORDER BY comment_id DESC";
                    $query = $this->db->query($sql);
                    ?>
                    <table cellspacing="0" cellpadding="0" class="table table-striped">
                        <tbody>
                        <?php
                        foreach ($query->result() as $lt) {
                            ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $lt->login_id; ?>
                                </th>
                                <td><?php echo $lt->content; ?></td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->created)); ?>">
                                        <?php echo $lt->created; ?>
                                    </time>
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

}
