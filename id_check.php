<?php
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$user_name = $_POST['user_name'];
if($_POST['user_name'] != NULL){
    $id_check = mq("select * from user_table where user_name ='$user_name'");
    $id_check = $id_check->fetch_array();

    if($id_check >= 1){
        echo "존재하는 아이디입니다.";
    } else {
        echo "존재하지 않는 아이디입니다.";
    }
}