<?php
//데이터베이스 연결하기
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$bid = $_GET['board_id'];
$pw = $_POST['pw'];
$sql = mq("SELECT * FROM board WHERE board_id ='$bid' and pw = password($pw);");
$row=mysqli_fetch_array($sql);

//$pw1 = $row['pw'];
//$pw2 = $_POST['pw'];
//if($row['pw'] == $_POST['pw'])
//if(password_verify($pw1,$pw2))
if($row)
{

    echo "<script>alert('수정 페이지로 이동합니다')</script>";

    echo "<script>location.href='modify.php?board_id=$bid'</script>";

}else{

    echo "<script>alert('비밀번호가 틀립니다.');history.go(-1)</script>";

}
?>
<!--<script type="text/javascript">alert("삭제되었습니다.");</script>-->
<!--<meta http-equiv="refresh" content="0 url=/" />-->