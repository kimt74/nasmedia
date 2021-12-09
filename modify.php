<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php";

$bid = $_GET['board_id'];
$sql = mq("select * from board where board_id ='$bid';");
$board = $sql->fetch_array();

$sql_file =mq("SELECT * FROM file WHERE board_id ='$bid' AND deleted_flag = 'n' ");
//$file = $sql_file -> fetch_array();
//$row = mysqli_fetch_all($sql_file);
$sql_file_num = mysqli_num_rows($sql_file);
$uid = $_SESSION['user_name'];

?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <!--<link rel="stylesheet" href="/css/style.css" />-->
</head>

<body>
<div id="board_write">
    <h1><a href="/">자유게시판</a></h1>
    <h4>글을 수정합니다.</h4>
    <div id="write_area">
        <form name="send_modify" action="process_modify.php?board_id=<?php echo $bid; ?>" method="post" enctype="multipart/form-data">
            <input  type="hidden" name="file_del_ids" id="file_del_ids">
            <div id="in_title">
                <textarea name="title" id="title" rows="1" cols="55" placeholder="제목" maxlength="100" required><?php echo $board['title']; ?></textarea>
            </div>
            <div class="wi_line"></div>
<!--            <div id="in_name">-->
<!--                <textarea name="user_name" id="user_name" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required>--><?php //echo $board['user_name']; ?><!--</textarea>-->
<!--            </div>-->
            <div class="wi_line"></div>
            <div id="in_content">
                <textarea style="width: 600px; height: 300px" name="content" id="content" placeholder="내용" required><?php echo strip_tags($board['content']); ?></textarea>
            </div>
<!--            <div id="in_pw">-->
<!--                <input type="password" name="pw" id="pw"  placeholder="비밀번호" required />-->
<!--            </div>-->
            <div id="in_file_list">
                <?php
                while($row_file = mysqli_fetch_array($sql_file)){;
                    ?>
<!--                <form action="process_modify_file.php?board_id=--><?php //echo $_GET['board_id']; ?><!--" method="post">-->

                <p class ="in_file_box" id="file_id_<?=$row_file['file_name']?>">
                파일 :<a href="./data/<?=$row_file['file_name']?>" download><?php echo $row_file['url']; ?></a>
<!--                <br><button class="in_file_box_btn" id="--><?//=$row_file['file_name']?><!--">삭제</button>-->
                    <input class="btnDel" data_index="<?=$row_file['file_id']?>"type="button" id="delBtn" value="삭제">
                </p>


                <?php }?>
                    <p><button type="button" class="new_file" id="file_add">추가</button>
                        <input type = "hidden" name = "MAX_FILE_SIZE" value ="300000"></input>

                <div id="in_file">
<!--                <p><input type="file" value="1" name="file_name[]" />-->
<!--                <button type="button" class="btnDel">Del</button></p>-->
            </div>
<!--        </form>-->
    </div>
            <a href="index.php">[본문 가기]</a>
            <a href="history.php?board_id=<?=$_GET['board_id']?>">[수정내역 보기]</a>
<!--            <a href="history.php">수정내역 보기</a>-->
            <div class="bt_se">
<!--                <button type="submit">글 작성</button>-->
                <input type="button" value="작성완료" name="done_btn" onclick="sendData();"/>

            </div>
        </form>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    let totalNum = '<?=$sql_file_num?>';
    totalNum *=1;
    let aDel = [];
    $(function(){
        $("#file_add").click(function(){ //file_add 버튼을 눌렀을때 ->이벤트 등록
            //let filename=[];
            //let fileadd = $(this).attr('file_add');
            let html = '<p><input type="file" value="1" name="file_name[]" />';
            html += '<button type="button" class="btnDel">Del</button></p>'; //html변수에 삭제버튼을 대입
            // filename.push(fileadd);
            //console.log();
            if(totalNum >= 5){
            window.alert("총 업로드 가능한 파일 갯수는 5개입니다");
        }
        else {
            $("#in_file").append(html); //in_file 아이디에 html을 추가해라
            totalNum = totalNum + 1;
            console.log(totalNum);
        }
        });

        $("#in_file_list").on("click", ".btnDel", function() {
            let nDelID = $(this).attr('data_index');
            aDel.push(nDelID);
            // console.log(aDel);
            $(this).parent().remove();
            totalNum = totalNum -1;
            // console.log(totalNum);
            console.log(aDel);



        });
    }




    )


    function sendDelData() {
    $.ajax({
        type: 'post',   //post 방식으로 전송
        url: 'process_modify_file.php',   //데이터를 주고받을 파일 주소
        data: {aDel: aDel},   //위의 변수에 담긴 데이터를 전송해준다.
        dataType: 'text',   //html 파일 형식으로 값을 담아온다.
        success: function () {   //파일 주고받기가 성공했을 경우. data 변수 안에 값을 담아온다.
            alert('성공: ' + aDel);  //현재 화면 위 id="message" 영역 안에 data안에 담긴 html 코드를 넣어준다.
        },
        error: function (request, error) {   //데이터 주고받기가 실패했을 경우 실행할 결과
            alert('실패');
            alert("code: " + request.status + " 2. message: " + request.responseText
                + "n" + "3.  error:" + error);
        }

    });
     }




</script>
<script type="text/javascript">
    function sendData(){
        var sendModifyFrom = document.send_modify;
        var user_title = sendModifyFrom.title.value;
        // var user_name = sendModifyFrom.user_name.value;
        var content = sendModifyFrom.content.value;
        // var pw = sendModifyFrom.pw.value;
        // var file_name[] = sendModifyFrom.file_name[].value();
        if(aDel.length > 0){
            $('#file_del_ids').val(JSON.stringify(aDel));
        }

        if(user_title && content){
            sendModifyFrom.submit();
        }else{
            alert("제목 내용을 모두 입력해주세요.")
        }
    }
</script>


</html>
