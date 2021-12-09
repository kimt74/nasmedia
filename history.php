<?php
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$bid = $_GET['board_id'];

?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>히스토리 내용 내역</title>

</head>
<body>
<div id="board_area">
    <h1>수정 내역</h1>
    <table class="list-table" border="1">
        <thead>
        <tr>
            <th width="70">번호</th>
            <th width="500">제목</th>
            <th width="120">글쓴이</th>
            <th width="100">작성일</th>
        </tr>
        </thead>
        <?php
        //리스트페이지
        $sql2 = mq("SELECT * FROM history WHERE deleted_flag = 'n' AND board_id ='$bid' order by history_id desc ");
        $total_num_history = mysqli_num_rows($sql2); //전체 히스토리 갯수
        //$sql3 = mq("select * from history WHERE deleted_flag = 'n' AND board_id = '$bid' order by history_id desc limit $page_start,$list");

        $history_num = $total_num_history;

        // histoty 테이블에서 id를 기준으로 내림차순해서 5개까지 표시
        //$sql = mq("select * from board WHERE deleted_flag = 'n' order by board_id desc"); //limit 0,5");
        while($history = $sql2->fetch_array())
        {
            //title변수에 DB에서 가져온 title을 선택
            $title=$history["title"];
            if(strlen($title)>30)
            {
                //title이 30을 넘어서면 ...표시
                $title=str_replace($history["title"],mb_substr($history["title"],0,30,"utf-8")."...",$history["title"]);
            }

            ?>
            <tbody>
            <tr>
                <td width="70"><?php echo $history_num--; ?></td>
                <td width="500"><a href="history_view.php?history_id=<?php echo $history["history_id"];?>"><?php echo $title;?></a></td>
                <td width="120"><?php echo $history['user_name']?></td>
                <td width="100"><?php echo $history['data_modify']?></td>
            </tr>
            </tbody>
        <?php } ?>
    </table>
    <ol>
    </ol>
    <a href="modify.php?board_id=<?=$bid?>;">[돌아가기]</a>
</body>

</html>
