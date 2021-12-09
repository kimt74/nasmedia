<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$pw = $_POST['pw'];
$uid = $_POST['user_name'];
if($_POST["user_name"] == "" || $_POST["pw"] == "") {
    echo '<script> alert("아이디나 패스워드 입력하세요"); history.back(); </script>';
}
else{
    $sql = mq("select * from user_table where user_name='".$_POST['user_name']."'");
    if(mysqli_num_rows($sql) == 1){ //아이디가 있다면 비번 검사
        $member = $sql->fetch_array();

        $sql2 = mq("SELECT * FROM user_table WHERE user_name ='$uid' and pw = password($pw);");
        $result = $sql2->fetch_array();
        if($result){
            $_SESSION['user_name'] = $result['user_name'];
            $_SESSION['pw'] = $result['pw'];
            echo "<script>alert('로그인되었습니다.'); location.href='index.php';</script>";
        }
        else{
            echo "<script>alert('아이디 혹은 비밀번호를 확인하세요.'); history.back();</script>";
        }

    }
}