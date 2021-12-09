<?php
include $_SERVER['DOCUMENT_ROOT']."/db.php"; //db 상대경로 설정
?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
   <!-- <link rel="stylesheet" type="text/css" href="/css/style.css" /> -->
</head>
<body>
<div id="board_area">

    <?php

    /* 검색 변수 */
    $subject = $_GET['subject'];
    $search_con = $_GET['search'];

    $val ='';
    if($subject === 'title'){
        $val = '제목';
    } else if($subject === 'user_name'){
        $val = '글쓴이';
    } else if($subject === 'content'){
        $val = '내용';
    }

    ?>
    <h1><?php echo $val; ?>에서 '<?php echo $search_con; ?>'검색결과</h1>
    <h4 style="margin-top:30px;"><a href="/">홈으로</a></h4>
    <table class="list-table" border = "1">
        <thead>
        <tr>
            <th style="width:70px">번호</th>
            <th style="width:500px">제목</th>
            <th style="width:120px">글쓴이</th>
            <th style="width:100px">작성일</th>
        </tr>
        </thead>
        <?php
        $sql2 = mq("select * from board where $subject like '%$search_con%' order by board_id desc");
        while($board = $sql2->fetch_array()){

        $title=$board["title"];
        if(strlen($title)>30)
        {
            $title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
        }
        //$sql3 = mq("select * from reply where con_num='".$board['board_id']."'");
        $rep_count = mysqli_num_rows($sql2);
        ?>
            <tbody>
        <tr>
            <td style="width:70px"><?php echo $board['board_id']; ?></td>
            <td style="width:500px">
<!--            --><?php
//            $lockimg = "<img src='/img/lock.png' alt='lock' title='lock' with='20' height='20' />";
//            if($board['lock_post']=="1")
//            { ?><!--<a href='/page/board/ck_read.php?idx=--><?php //echo $board["idx"];?><!--'>--><?php //echo $title, $lockimg;
//            }else{
//
//            }?>
<!--                --><?php
//                $boardtime = $board['created']; //$boardtime변수에 board['created']값을 넣음
//                $timenow = date("Y-m-d"); //$timenow변수에 현재 시간 Y-M-D를 넣음
//
//                if($boardtime==$timenow){
//                    $img = "<img src='/img/new.png' alt='new' title='new' />";
//                }else{
//                    $img ="";
//                }
//                ?>
                <a href="view.php?board_id=<?php echo $board["board_id"];?>"><?php echo $title;?></a>
            <td width="120"><?php echo $board['user_name']?></td>
            <td width="100"><?php echo $board['created']?></td>

        </tr>
            </tbody>
        <?php } ?>
    </table>
    <!-- 18.10.11 검색 추가 -->
    <div id="search_box2">
        <form action="search_result.php" method="GET">
            <select name="subject">
                <option value="title">제목</option>
                <option value="user_name">글쓴이</option>
                <option value="content">내용</option>
            </select>
            <input type="text" name="search" size="40" required="required"/> <button>검색</button>
        </form>
    </div>
</div>
</body>
</html>