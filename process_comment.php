<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php";

$bid = $_GET['board_id'];
//$pw =$_POST['pw'];
$content = $_POST['content'];
$pw = $_SESSION['pw'];
$uid = $_SESSION['user_name'];

//login version
if($bid && $uid && $pw && $content){
    $sql = mq("insert into comment(board_id,class,user_name,pw,content,created) 
values('".$bid."','0','".$uid."','".$pw."','".$content."', NOW())");
    echo "<script>alert('댓글이 작성되었습니다.'); 
    
        location.href='comment.php?board_id=$bid';</script>";
}
else{
    echo "<script>alert('댓글 작성에 실패했습니다.'); 
        history.back();</script>";
}





//not login version
//if($bid && $_POST['user_name'] && $pw && $content){
//    $sql = mq("insert into comment(board_id,class,user_name,pw,content,created)
//values('".$bid."','0','".$_POST['user_name']."',password($pw),'".$content."', NOW())");
//    echo "<script>alert('댓글이 작성되었습니다.');
//
//        location.href='comment.php?board_id=$bid';</script>";
//}
//else{
//    echo "<script>alert('댓글 작성에 실패했습니다.');
//        history.back();</script>";
//}
