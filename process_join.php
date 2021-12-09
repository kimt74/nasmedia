<?php
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$user_name = $_POST['user_name'];
$pw =$_POST['pw'];
$email = $_POST['email'];


//입력받은 데이터를 DB에 저장
$query_check = mq("select * from user_table where user_name='$user_name'");
$query_check = $query_check->fetch_array();
//$sql = "insert into user (user_name, pw,email, created)
// values ('$user_name', password($pw), '$email', now())";

if($query_check >= 1){
    echo "<script>alert('아이디가 중복됩니다.'); history.back();</script>";
}else {
    $sql = mq("insert into user_table (user_name, pw,email, created)
 values ('$user_name', password($pw), '$email', now())");

    if($sql){
        ?>
        <meta charset="utf-8" />
<script type="text/javascript">alert('회원가입이 완료되었습니다.');</script>
<meta http-equiv="refresh" content="0 url=/">

        <?php
        } else{
        echo "저장하는데 문제가 발생하였습니다 관리자에게 문의 하십시오";

    }

} ?>



