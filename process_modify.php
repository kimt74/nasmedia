<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php";

$bid= $_GET['board_id'];
//$user_name = $_POST['user_name'];
//$user_pw = $_POST['pw'];
$title = $_POST['title'];
$content = $_POST['content'];
$aDelIds = @json_decode($_POST['file_del_ids']);
$uid = $_SESSION['user_name'];
$pw = $_SESSION['pw'];




//$sql = mq("update board set title='".$title."',pw=password('".$user_pw."'),user_name='".$user_name."',content='".$content."' where board_id='".$bid."'");
$sql = mq("update board set title='".$title."',pw='".$pw."' ,user_name='".$uid."',content='".$content."' where board_id='".$bid."'");


$sql_history ="
INSERT INTO history
    (board_id, title, content, user_name, data_modify)
    VALUES(
        '$bid',
        '$title',
        '$content',
        '$uid',
        NOW()
    )

";
mq($sql_history);




if(@count($aDelIds) >0 ){ //php 7.2 버전에서 오류 메세지 생성

    $sDel = implode(',', $aDelIds);
//    $result = str_replace("[","", $deleted_id);
//    $result2 = str_replace("]","", $result);
    $count_sDel = @count($aDelIds);
    $sql_1 = "
UPDATE file SET deleted_flag = 'y' WHERE file_id IN ($sDel)

";
//$sql_file = mq("UPDATE file set deleted_flag = 'y' WHERE file_id = '$deleted_id';");
    mq($sql_1);


    $sql3 = mq("update board set file_count = file_count - '$count_sDel'  where board_id= '$bid'");
}


//echo $_POST['file_name'];
//if(@count($_POST['file_name'])> 0 ){
//if(isset($_POST['file_name'])){
$o_name = @$_FILES['file_name']['name']; //original name
if(is_array($o_name)){
    $filesize = 0;
    foreach($_FILES['file_name']['size'] as $key => $val){
        if($_FILES['file_name']['size'][$key] > 0){
            $filesize += $_FILES['file_name']['size'][$key];
        }
    }
    // 사용자 입력필드 파일 제한 크기
    $maxfilesize = (int)$_POST['MAX_FILE_SIZE'];
    if($filesize > $maxfilesize){
        echo "허용 파일용량을 초과하였습니다.";
    } else {
        foreach($_FILES['file_name']['name'] as $key => $val){
            if($_FILES['file_name']['size'][$key] > 0){
                if($_FILES['file_name']['error'][$key] === UPLOAD_ERR_OK){
                    if(is_uploaded_file($_FILES['file_name']['tmp_name'][$key])){
//                        $filename = md5("habony_".$_FILES['file_name']['name'][$key]);
                        $filename = $_FILES['file_name']['name'][$key];
                        $tmpfile = $_FILES['file_name']['tmp_name'][$key];
                        $folder = "./data/".$filename;
                        $uploadFile = move_uploaded_file($tmpfile,$folder);
                        if($uploadFile){

                            $sql2 = "
INSERT INTO file
(board_id,file_name, server_name, url, created)
VALUES('$bid',
       '$o_name[$key]',
       '$tmpfile',
       '$folder',
       NOW()      
)
";

                            mq($sql2);
                            $sql3 = mq("update board set file_count = file_count + 1 where board_id= '$bid'");
                        }
                    }
                }
            }
        }
    }
}



//}



?>









<script type="text/javascript">alert("수정되었습니다."); </script>
<meta http-equiv="refresh" content="0 url=view.php?board_id=<?php echo $bid; ?>">