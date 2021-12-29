
    <script>
        $(document).ready(function() {
            $("#write_btn").click(function() {
                if ($("#input01").val() == '') {
                    alert('제목을 입력해 주세요.');
                    $("#input01").focus();
                    return false;
                } else if ($("#input02").val() == '') {
                    alert('내용을 입력해 주세요.');
                    $("#input02").focus();
                    return false;
                } else {
                    $("#write_action").submit();
                }
            });
        });
    </script>
    <article id="board_area">
        <header>
            <h1></h1>
        </header>
        <form class="form-horizontal" method="post" action="" id="write_action" enctype="multipart/form-data"l>
<!--        --><?php
//        $attributes = array('class' => 'form-horizontal', 'id' => 'write_action');
//        echo form_open('board/', $attributes);
//        ?>
            <fieldset>
                <legend>
                    게시물 쓰기
                </legend>
                <div class="control-group">
                    <label class="control-label" for="input01">제목</label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" id="input01" name="title">
                        <p class="help-block">
                            게시물의 제목을 써주세요.
                        </p>
                    </div>
                    <label class="control-label" for="input02">내용</label>
                    <div class="controls">
                        <textarea class="input-xlarge" id="input02" name="content" rows="5"></textarea>
                        <p class="help-block">
                            게시물의 내용을 써주세요.
                        </p>
                    </div>

                <!--파일업로드-->
                    <input type="hidden" name="MAX_FILE_SIZE" value="300000"></input>


                    <p>
                        <button type="button" class="new_file" id="file_add">첨부파일</button>

                    <div class="file_input" id="in_file">


                    </div>






                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="write_btn">
                            작성
                        </button>
                        <button class="btn" onclick="history.back()" type="button">
                            취소
                        </button>
                    </div>
                </div>
            </fieldset>
        </form>
    </article>
<script type="text/javascript">

    let totalNum = 0;
    $(function () {
        $("#file_add").click(function () { //file_add 버튼을 눌렀을때 ->이벤트 등록
            let html = '<p><input type="file" value="1" name="file_name[]" />';
            html += '<button type="button" class="btnDel">Del</button></p>'; //html변수에 삭제버튼을 대입
            if (totalNum >= 5) {
                window.alert("총 업로드 가능한 파일 갯수는 5개입니다");
            } else {
                $("#in_file").append(html); //in_file 아이디에 html을 추가해라
                totalNum = totalNum + 1;
                console.log(totalNum);
            }
        });

        $("#in_file").on("click", ".btnDel", function () {
            $(this).parent().remove();
            totalNum = totalNum - 1;
        });


    }
    )


</script>
