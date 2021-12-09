<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php";

$bid = $_GET['board_id'];
//$pw =$_POST['pw'];
$content = $_POST['content'];
$cid = $_POST['comment_id'];
$pw = $_SESSION['pw'];
$uid = $_SESSION['user_name'];
//login ver
if($cid){
    $sql = mq("insert into comment(parent_id,board_id,class,user_name,pw,content,created) 
values('".$cid."','".$bid."','1','".$uid."','".$pw."','".$content."', NOW())");
    echo "<script>alert('대댓글이 작성되었습니다.'); 
        location.href='comment.php?board_id=$bid';</script>";
}
else{
    echo "<script>alert('댓글 작성에 실패했습니다.'); 
        history.back();</script>";
}



//without login
//if($cid){
//    $sql = mq("insert into comment(parent_id,board_id,class,user_name,pw,content,created)
//values('".$cid."','".$bid."','1','".$_POST['user_name']."',password($pw),'".$content."', NOW())");
//    echo "<script>alert('대댓글이 작성되었습니다.');
//        location.href='comment.php?board_id=$bid';</script>";
//}
//else{
//    echo "<script>alert('댓글 작성에 실패했습니다.');
//        history.back();</script>";
//}