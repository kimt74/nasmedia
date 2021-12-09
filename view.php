<?php
session_start();
$conn = mysqli_connect(
    'localhost',
    'root',
    'autoset',
    'myboard');

//$uid = $_SESSION['user_name'];

if(isset($_GET['board_id'])) //id 가 존재하는지 체크
{
    $filtered_id = mysqli_real_escape_string($conn, $_GET['board_id']);//sql injection 체크
    $sql = "SELECT * FROM board WHERE board_id ={$filtered_id}";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

//    $sql_count = "SELECT COUNT(file_count) as cnt FROM board WHERE board_id ={$filtered_id}";
//    $sql_file ="SELECT * FROM file WHERE board_id ={$filtered_id}";
//    $result_file = mysqli_query($conn,$sql_file);
//    $row_file = mysqli_fetch_array($result_file);
    
    
    
    //$escapted_title = htmlspecialchars($row['title']);

    //htmlspecialchars로 css 방어
    $article = array(
        'title' => htmlspecialchars($row['title']),
//        'content' => htmlspecialchars($row['content']),
        'content' => $row['content'],
        'user_name' => htmlspecialchars($row['user_name']),
        'created' => htmlspecialchars($row['created']),
        'file_count' => htmlspecialchars($row['file_count'])
    );

}

?>
<!doctype html>
<html>
<head>
    <body>
    <table class = "view-table" border = "1">
        <thead>
        <tr>
            <th width="500">제목</th>
            <th width="120">글쓴이</th>
            <th width="100">작성일</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="500"><?=$article['title']?></td>
            <td width="120"><?=$article['user_name']?></td>
            <td width="100"><?=$article['created']?></td>


        </tr>
        </tbody>
    </table>
<div style="width:742px; height:400px; background-color:#ABEBC6"><?=$article['content']?></div>
    <?php
    if(isset($_SESSION['user_name'])){
        ?>
    <div><a href="comment.php?board_id=<?=$_GET['board_id']?>">[댓글달기]</a></div>
    <?php }
    ?>

<!--<div><a href="comment.php?board_id=--><?//=$_GET['board_id']?><!--">댓글달기</a></div>-->


<div class="file_view">
    <?php
    $filtered_id = mysqli_real_escape_string($conn, $_GET['board_id']);//sql injection 체크
    $sql_count = "SELECT COUNT(file_count) as cnt FROM board WHERE board_id ={$filtered_id} AND deleted_flag ='n'";
    $sql_file ="SELECT * FROM file WHERE board_id ={$filtered_id} AND deleted_flag ='n'";
    $result_file = mysqli_query($conn,$sql_file);


    while($row_file = mysqli_fetch_array($result_file)){
    ?>
            파일 : <a href="./data/<?=$row_file['file_name']?>" download><?php echo $row_file['url']; ?></a><br>
    <?php  } ?>

</div>
<!--<div style="width:150px; height:30px; background-color:#EAFAF1; margin:30px;">업로드한 파일수 :--><?//=$article['file_count']?><!--</div>-->
<div style="width:180px; height:30px; background-color:#EAFAF1; margin:30px;">업로드 가능한 파일수: <?= 5-$article['file_count']?></div>

    <?php
    if(isset($_SESSION['user_name']) && $_SESSION['user_name'] == $article['user_name'] ){
        ?>

<a href="modify_check.php?board_id=<?=$_GET['board_id']?>">[수정하기] </a>
<a href="delete.php?board_id=<?=$_GET['board_id']?>">[삭제하기]</a>

        <?php
    }
    ?>

    <a href="index.php">[홈화면]</a>
<a href="download_excel.php?board_id=<?=$_GET['board_id']?>">[다운받기]</a>
    </body>
</head>
</html>
