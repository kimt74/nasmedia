<?php
$conn = mysqli_connect(
    'localhost',
    'root',
    'autoset',
    'myboard');
//$bid = mysqli_insert_id($conn);
if(isset($_GET['history_id'])) //id 가 존재하는지 체크
{
    $filtered_id = mysqli_real_escape_string($conn, $_GET['history_id']);//sql injection 체크
    $sql = "SELECT * FROM history WHERE history_id ={$filtered_id}";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    //htmlspecialchars로 css 방어
    $article = array(
        'title' => htmlspecialchars($row['title']),
//        'content' => htmlspecialchars($row['content']),
        'user_name' => htmlspecialchars($row['user_name']),
        'data_modify' => htmlspecialchars($row['data_modify'])
    );
    $content = $row['content'];
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
        <th width="100">날짜</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="500"><?=$article['title']?></td>
        <td width="120"><?=$article['user_name']?></td>
        <td width="100"><?=$article['data_modify']?></td>


    </tr>
    </tbody>
</table>
<div style="width:742px; height:400px; background-color:#FCD1FF"><?=$content?></div>
<a href="javascript:history.back()">[돌아가기]</a>
<a href="index.php">[본문으로]</a>
</body>
</head>
</html>
