<?php
session_start();
header('Content-Type: text/html; charset=utf-8'); // utf-8인코딩
$db = new mysqli("localhost","root","autoset","myboard"); //db 변수에 mysql 연결
$db->set_charset("utf8"); //utf8 인코딩
//sql injection 체크
//$user_pw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
$filtered = array(
    'title' => mysqli_real_escape_string($db, $_POST['title']),
    'content' => mysqli_real_escape_string($db, $_POST['content'])
//    'user_name' => mysqli_real_escape_string($db, $_POST['user_name'])
);
//$title = $_POST['title'];
//$content = $_POST['content'];
//echo $title + $content;
//$pw = $_POST['pw'];
$uid = $_SESSION['user_name'];
$pw = $_SESSION['pw'];
$sql = "
  INSERT INTO board
    (title, content, user_name, pw, created)
    VALUES(
        '{$filtered['title']}',
        '{$filtered['content']}',
        '$uid',
        '$pw',
        NOW()
    )
";

function mq($sql)
{
    global $db; // $db를 전역변수로
    return $db->query($sql);
}
$result = mq($sql);
if ($result === false) {
    echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
    error_log(mysqli_error($db)); //아파치 에러로그에 저장
} else {
    $bid = mysqli_insert_id($db);


//file
//$tmpfile =  $_FILES['file_name']['tmp_name'];
@$o_name = $_FILES['file_name']['name']; //original name
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
                            $result_file = mq($sql2);
                            echo $o_name[$key];

                            $sql3 = mq("update board set file_count = file_count + 1 where board_id= '$bid'");
                        }
                    }
                }
            }
        }
    }
}


    $sql_history ="
INSERT INTO history
    (board_id, title, content, user_name, data_modify)
    VALUES(
        '$bid',
        '{$filtered['title']}',
        '{$filtered['content']}',
        '$uid',
        NOW()
    )

";
    $result_history = mq($sql_history);

//    "UPDATE file A INNER JOIN board B
//    ON A.board_id = B.board_id
//    SER B.file_count = COUNT(A.board_id = B.board_id)";
//    mq($sql2);
//    $sql3 = mq("update board set file_count = file_count + 1 where board_id= '$bid'");

        echo '성공했습니다. <a href="index.php">메인화면으로 돌아가기</a>';

}

//file
//$id = mysqli_insert_id();


