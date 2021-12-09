<?php
//데이터베이스 연결하기
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$bid = $_GET['board_id'];
$pw = $_POST['pw'];
//$sql = mq("SELECT pw FROM board WHERE board_id ='$bid';");
$sql = mq("SELECT * FROM board WHERE board_id ='$bid' and pw = password($pw);");
$row=mysqli_fetch_array($sql);

//
//if($row['pw'] == $_POST['pw'])
if($row)
    {

      $sql2 = mq("UPDATE board set deleted_flag = 'y' WHERE board_id='$bid';");
        echo "<script> alert('삭제 되었습니다.');</script>";
    }else{

      echo "<script>alert('비밀번호가 틀립니다.');history.go(-1)</script>";

}
?>
<meta http-equiv="refresh" content="0 url=/" />