<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php"; /* db load */
$uid =$_SESSION['user_name'];
$pw = $_SESSION['pw'];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>NAS BOARD</title>
    <script type="text/javascript" src="/smarteditor/js/service/HuskyEZCreator.js" charset="UTF-8"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>

</head>
<body>
<h1>글 쓰기</h1>

    <li>글을 작성하여 주십시오 </li>
<form id="send_write" name="send_write" action="process_write.php" method = "POST" enctype="multipart/form-data">
<!--    <p><input type = "text" name="user_name" placeholder="name" required id="name">--><?//=$uid?><!-- </p>-->
<!--    <p><input type = "password" name="pw" placeholder="password" required id="password"> </p>-->
    <p>ID: <?=$uid?></p>
    <p><input style="width: 591px; height: 20px" type = "text" name="title" placeholder="title" required id="title"></p>
    <p><textarea style="width: 600px; height: 300px" name ="content" placeholder="content" required id = "content"></textarea></p>
<!--     textarea 밑에 script 작성하기 -->
    <script id="smartEditor" type="text/javascript">
        let oEditors = [];
        nhn.husky.EZCreator.createInIFrame({
            oAppRef: oEditors,
            elPlaceHolder: "content",  //textarea ID 입력
            sSkinURI: "/smarteditor/SmartEditor2Skin.html",  //martEditor2Skin.html 경로 입력
            fCreator: "createSEditor2",
            htParams : {
                // 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                bUseToolbar : true,
                // 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                bUseVerticalResizer : false,
                // 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                bUseModeChanger : false
            }
        });
    </script>

    <input type = "hidden" name = "MAX_FILE_SIZE" value ="300000"></input>



    <p><button type="button" class="new_file" id="file_add">첨부파일</button>

    <div class="file_input" id="in_file" >
<!--            <input type="file" value="1" name="file_name[]" />-->
<!--        <input type="file" value="1" name="file_name[]" />-->
<!--        <input type="file" value="1" name="file_name[]" />-->
<!--        <input type="file" value="1" name="file_name[]" />-->
<!--        <input type="file" value="1" name="file_name[]" />-->


    </div>
<!--    <p><input type = "submit"></p>-->
<!--    <input type="button" value="작성완료" name="done_btn" onclick="sendData();"/>-->

<!--        <input type="button" name="done_btn" id="smart_editor_btn" value="제출">-->

</form>
<input type="button" name="done_btn" id="smart_editor_btn" value="제출">

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    let totalNum = 0;
    $(function(){
            $("#file_add").click(function(){ //file_add 버튼을 눌렀을때 ->이벤트 등록
                let html = '<p><input type="file" value="1" name="file_name[]" />';
                html += '<button type="button" class="btnDel">Del</button></p>'; //html변수에 삭제버튼을 대입
                if(totalNum >= 5){
                    window.alert("총 업로드 가능한 파일 갯수는 5개입니다");
                }
                else {
                    $("#in_file").append(html); //in_file 아이디에 html을 추가해라
                    totalNum = totalNum + 1;
                    console.log(totalNum);
                }
            });

            $("#in_file").on("click", ".btnDel", function() {
                $(this).parent().remove();
                totalNum = totalNum -1;
            });



            $("#smart_editor_btn").click(function(){
                oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);
                let sendWriteFrom = document.send_write;
                let title = sendWriteFrom.title.value;
                // let user_name = sendWriteFrom.user_name.value;
                // let content = sendWriteFrom.content.value;
                // let pw = sendWriteFrom.pw.value;



                //폼 submit
                // let queryString = $("form[name=send_write]").serialize();
                let formData = new FormData($("#send_write")[0]);
                // let queryString = $("#send_write").serialize();
                // console.log(formData);
                if(title) //스마트에디터 사용으로 인해 태그가 같이 들어가서 null이아니다
                {
                    // $("#send_write_id").submit();
                    $.ajax({
                        type: 'POST',   //post 방식으로 전송
                        enctype: 'multipart/form-data',  // 이거만으로 안된다 더 필요한게 있다
                        url: 'process_write.php',   //데이터를 주고받을 파일 주소
                        data: formData ,   //위의 변수에 담긴 데이터를 전송해준다.{ "title": title, "content": content }
                        processData: false,
                        contentType: false,
                        cache: false,
                        //dataType: 'json',   //html 파일 형식으로 값을 담아온다.
                        success: function () {   //파일 주고받기가 성공했을 경우. data 변수 안에 값을 담아온다.
                            alert('업로드에 성공하였습니다');
                        },
                        error: function (request, error) {   //데이터 주고받기가 실패했을 경우 실행할 결과
                            alert('실패');
                            alert("code: " + request.status + " 2. message: " + request.responseText
                                + "n" + "3.  error:" + error);
                        }

                    });
                }
                else{
                    alert("제목은 꼭 입력 하세요")
                }
            })



    }






    )
</script>

</form>



</html>
