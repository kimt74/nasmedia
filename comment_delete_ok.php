<?php
//데이터베이스 연결하기
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$cid = $_GET['comment_id'];
$pw = $_POST['pw'];
//$sql = mq("SELECT pw FROM board WHERE board_id ='$bid';");
$sql = mq("SELECT * FROM comment WHERE comment_id ='$cid' and pw = password($pw);");
$row = mysqli_fetch_array($sql);
//$sql_board_id = mq("SELECT * FROM comment WHERE comment_id ='$cid';");

if($row)
{

    $sql2 = mq("UPDATE comment set deleted_flag = 'y' WHERE comment_id= '$cid';");
    echo "<script> alert('삭제 되었습니다.');history.go(-2)</script>";
}else{

    echo "<script>alert('비밀번호가 틀립니다.');history.go(-1)</script>";

}

