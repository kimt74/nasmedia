<script type="text/javascript" src="/include/js/httpRequest.js"></script>
<script type="text/javascript">
    function comment_add() {
        var csrf_token = getCookie('csrf_cookie_name');
        var name = "content=" + encodeURIComponent(document.com_add.content.value) +
            "&csrf_test_name=" + csrf_token + "&table=comment&board_id=<?php echo $this->input->get('id', TRUE); ?>";
        sendRequest("/ajax_board/ajax_comment_add", name, add_action, "POST");
    }

    function add_action() {
        if (httpRequest.readyState == 4) { //데이터 전송이 완료된상태이면 실행
            if (httpRequest.status == 200) { //웹서버 응답 성공 이면 실행
                if (httpRequest.responseText == 1000) { //댓글내용이 없습니다
                    alert("댓글의 내용을 입력하세요.");
                } else if (httpRequest.responseText == 2000) { //데이터베이스 입력중 에러
                    alert("다시 입력하세요.");
                } else if (httpRequest.responseText == 9000) { //로그인 필요
                    alert("로그인하여야 합니다.");
                } else {
                    var content = document.getElementById("comment_area");
                    content.innerHTML = httpRequest.responseText;

                    var textareas = document.getElementById("input01");
                    textareas.value = '';
                }
            }
        }
    }

    function getCookie(name) {
        var nameOfCookie = name + "=";
        var x = 0;

        while (x <= document.cookie.length) {
            var y = (x + nameOfCookie.length);

            if (document.cookie.substring(x, y) == nameOfCookie) {
                if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                    endOfCookie = document.cookie.length;

                return unescape(document.cookie.substring(y, endOfCookie));
            }

            x = document.cookie.indexOf(" ", x) + 1;

            if (x == 0)

                break;
        }
    }

</script>


<article id="board_area">
    <header>
        <h1></h1>
    </header>
    <table cellspacing="0" cellpadding="0" class="table table-striped" border="1">
        <thead>
        <tr>
            <th scope="col">제목: <?php if (isset($views)) {
                    echo $views->title;
                } ?></th>
            <th scope="col">이름: <?php echo $views->login_id; ?></th>
            <th scope="col">조회수: <?php echo $views->hits; ?></th>
            <th scope="col">등록일: <?php echo $views->created; ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th colspan="4">
                <?php echo $views->content; ?>
            </th>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4">
                <a href="/board/?per_page=<?= $this->input->get('per_page', TRUE); ?>" class="btn btn-primary">목록 </a>
                <!--                    <a href="javascript:history.back();" class="btn btn-primary">목록</a>-->
                <a href="/board/modify?id=<?= $this->input->get('id'); ?>&per_page=<?= $this->input->get('per_page', TRUE) ?>"
                   class="btn btn-warning"> 수정 </a>
                <a href="/board/delete?id=<?= $this->input->get('id'); ?>&per_page=<?= $this->input->get('per_page', TRUE) ?>"
                   class="btn btn-danger"> 삭제 </a>
            </th>
        </tr>
        </tfoot>
    </table>

    <form class="form-horizontal" method="POST" action="" name="com_add">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="input01">댓글</label>
                <div class="controls">
                    <textarea class="input-xlarge" id="input01" name="content" rows="3"></textarea>
                    <input class="btn btn-primary" type="button" onclick="comment_add()" value="작성" />
                    <p class="help-block"></p>
                </div>
            </div>
        </fieldset>
    </form>
    <div id="comment_area">
        <table cellspacing="0" cellpadding="0" class="table table-striped">
            <tbody>
            <?php
            if (!empty($comment_list)) {
                foreach ($comment_list as $lt) {
                    ?>
                    <tr>
                        <th scope="row">
                            <?php echo $lt->login_id;?>
                        </th>
                        <td><?php echo $lt->content;?></td>
                        <td>
                            <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->created)); ?>">
                                <?php echo $lt->created;?>
                            </time>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>

            <div style="text-indent: 50px;" class="comment reply view" id="comment_reply_area">


            </div>



            <div class="comment_reply" id="comment_reply" style="text-indent: 50px">

            </div>



            </tbody>
        </table>
    </div>


</article>

<footer id="footer">
    <dl>
        <dt>
        </dt>
        <dd>
        </dd>
    </dl>
</footer>
</div>
</body>
</html>


