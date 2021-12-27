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

                <a href="/board/?per_page=<?= $this->input->get('per_page', TRUE); ?>" class="btn btn-primary">[목록]</a>
                <!--                    <a href="javascript:history.back();" class="btn btn-primary">목록</a>-->
                <a href="/board/modify?id=<?= $this->input->get('id'); ?>&per_page=<?= $this->input->get('per_page', TRUE) ?>"
                   class="btn btn-warning">[수정]</a>
                <a href="/board/delete?id=<?= $this->input->get('id'); ?>&per_page=<?= $this->input->get('per_page', TRUE) ?>"
                   class="btn btn-danger">[삭제]</a>
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
                    <input class="btn btn-primary" type="button" id="comment_add" value="작성"/>
                    <p class="help-block"></p>
                </div>
            </div>
        </fieldset>
    </form>
    <div id="comment_area">

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
<script type="text/javascript">

    $(function () {

        getCommentList();

        $("#comment_add").click(function () {
            $.ajax({
                url: "/Board/ajax_comment_add",
                type: "POST",
                data: {
                    "content": encodeURIComponent($("#input01").val()),
                    "csrf_test_name": getCookie('csrf_cookie_name'),
                    "table": "comment",
                    "board_id": "<?= $this->input->get('id', TRUE); ?>"
                },
                dataType: "json",

                complete: function (xhr, textStatus) {
                    if (textStatus == 'success') {
                        if (xhr.responseText == 1000) {
                            alert('댓글 내용을 입력하세요.');
                        } else if (xhr.responseText == 2000) {
                            alert('다시 입력하세요.');
                        } else if (xhr.responseText == 9000) {
                            alert('로그인해야 합니다.');
                        } else {
                            getCommentList();
                            // alert($("#comment_area").html());
                            //       $("#comment_area").html(xhr.responseText);
                            $("#input01").val('');
                        }
                    }
                }
            });
        });

        $(document).on('click', '#comment_delete', function () {
            //console.log(">>>", $(this).closest('tr').attr('comment_id'));
            //console.log(">>>", $(this).attr('comment_id'));
            var comment_id = $(this).closest('tr').attr('comment_id');
            $.ajax({
                url: '/Board/ajax_comment_delete',
                type: 'POST',
                data: {
                    "csrf_test_name": getCookie('csrf_cookie_name'),
                    "table": "comment",
                    "board_id": "<?= $this->input->get('id', TRUE); ?>",
                    "comment_id": comment_id
                },
                dataType: "json",
                complete: function (xhr, textStatus) {
                    if (textStatus == 'success') {
                        if (xhr.responseText == 9000) {
                            alert('로그인해야합니다.');
                        } else if (xhr.responseText == 8000) {
                            alert('본인의 댓글만 삭제할 수 있습니다.');
                        } else if (xhr.responseText == 2000) {
                            alert('다시 삭제하세요.');
                        } else {
                            $('#row_num_' + xhr.responseText).remove();
                            alert('삭제되었습니다.');
                        }
                    }
                }
            });
        });


        $(document).on('click', '#comment_reply', function () {
            console.log(">>>", $(this).closest('tr').attr('comment_id'));
            var parent_id = $(this).closest('tr').attr('comment_id');
            $("td.comment_reply_content#comment_reply_content_id_" + parent_id).toggle(0);

            $("#comment_reply_add").click(function () {
                // var parent_id = $(this).closest('td').attr('comment_id');
                // console.log(">>>", $(this).closest('tr').attr('comment_id'));
                console.log(">>>", parent_id);
                $.ajax({
                    url: '/Board/ajax_comment_add',
                    type: 'POST',
                    data: {
                        "content": encodeURIComponent($("#input02").val()),
                        "csrf_test_name": getCookie('csrf_cookie_name'),
                        "table": "comment",
                        "board_id": "<?= $this->input->get('id', TRUE); ?>",
                        "parent_id": parent_id
                    },
                    dataType: "json",

                    complete: function (aData) {
                        if (aData.result === "200") {
                            alert("등록되었습니다.");
                            getCommentList();
                        }else{
                            alert("실패");
                        }
                    }

                });
                // console.log(">>>", xhr);
            });


        });

    });


    var getCommentList = function(){
        $.ajax({
            url: '/board/getCommentList',
            type: 'POST',
            data: {
                "csrf_test_name": getCookie('csrf_cookie_name'),
                "table": "comment",
                "board_id": "<?= $this->input->get('id', TRUE); ?>",
            },
            dataType: "json",
            success: function (aData) {
                $("#comment_area").html(aData.sHtml);

                $("td.comment_reply_content").hide();
            }
        });
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



