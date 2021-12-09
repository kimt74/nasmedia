<?php include  $_SERVER['DOCUMENT_ROOT']."/db.php";
session_start();
if(isset($_GET["page"])){
    $page =$_GET["page"];
}
else{
    $page =1;
}

if(isset($_SESSION['user_name'])){
    echo $_SESSION['user_name']; ?> 님 안녕하세요
    <br/>
    <a href="./logout.php"><input type="button" value="로그아웃" /></a>
    <?php
}
else {
    ?><button onclick="location.href='./login.php'">로그인</button>
    <br/>
<?php   }
?>



<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>

</head>
<body>
<div id="board_area">
    <h1>자유게시판</h1>
    <h4>우리들의 생각을 공유해요</h4>

    <div id="search_box"> <!--타이틀 아래 검색박스 만들기-->
        <form action="search_result.php" method="get">
            <select name="subject"> <!--<select>태그 제목, 글쓴이, 내용으로 검색할 수 있게
             하였으며 검색버튼을 누르면 해당 선택한 select 에 따라값이 전송됨-->
                <option value="title">제목</option>
                <option value="user_name">글쓴이</option>
                <option value="content">내용</option>
            </select>
            <input type="text" name="search" size="40" required="required" /> <button>검색</button>
        </form>
    </div>


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
        $sql2 = mq("SELECT * FROM board WHERE deleted_flag = 'n'");
        $total_num_board = mysqli_num_rows($sql2); //전체 게시글 갯수
        $list = 5; // 한페이지에 보여줄 갯수
        $block_cnt = 5;
        $block_num = ceil($page/$block_cnt);
        $block_start = (($block_num-1)*$block_cnt)+1; //블록 시작번호 1, 6,11..
        $block_end =$block_start+$block_cnt -1; //블록의 마지막 번호 5, 10, 15..

        $total_page = ceil($total_num_board/$list);
        if($block_end > $total_page){
            $block_end = $total_page;
        }
        $total_block =ceil($total_page/$block_cnt);
        $page_start = ($page -1)*$list;

        $sql3 = mq("select * from board WHERE deleted_flag = 'n' order by board_id desc limit $page_start,$list");

        $board_num = $total_num_board - (($page-1) * $list);



        // board테이블에서 id를 기준으로 내림차순해서 5개까지 표시
        //$sql = mq("select * from board WHERE deleted_flag = 'n' order by board_id desc"); //limit 0,5");
        while($board = $sql3->fetch_array())
        {
            //title변수에 DB에서 가져온 title을 선택
            $title=$board["title"];
            if(strlen($title)>30)
            {
                //title이 30을 넘어서면 ...표시
                $title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
            }


            ?>
            <tbody>
            <tr>
                <td width="70"><?php echo $board_num--; ?></td>
                <td width="500"><a href="view.php?board_id=<?php echo $board["board_id"];?>"><?php echo $title;?></a></td>
                <td width="120"><?php echo $board['user_name']?></td>
                <td width="100"><?php echo $board['created']?></td>

            </tr>
            </tbody>
        <?php } ?>
    </table>
    <div id = "page_num" style = "text-align: center";>
        <?php
        if ($page <= 1){
            //empty
        }
        else{
            echo "<a href = 'index.php?page=1'>처음</a>";
        }
        if($page <= 1){

        }
        else{
            $pre = $page -1;
            echo "<a href = 'index.php?page=$pre'>◁ 이전</a>";
        }
        for($i = $block_start;$i <= $block_end; $i++){
            if($page == $i){
                echo "<b> $i </b>";
            }
            else {
                echo "<a href = 'index.php?page=$i'> $i </a>";

            }
        }
        if($page >= $total_page){

        }
        else{
            $next = $page +1;
            echo "<a href = 'index.php?page=$next'> 다음 ▷ </a>";
        }

        if($page >= $total_page){

        }
        else{
            $next = $page +1;
            echo "<a href = 'index.php?page=$total_page'> 마지막 </a>";
        }
        ?>
    </div>

    <ol>
    </ol>
    <?php
    if(isset($_SESSION['user_name'])){
        ?>
        <button type="button" class="btn btn-primary"><a href = "write.php"> 글쓰기 </a></button>
        <?php
    }
    else{
        echo "글쓰기는 로그인 후 가능합니다";
    }
    ?>

</body>

</html>