<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/db.php"; /* db load */
$bid =$_GET['board_id'];
$uid =$_SESSION['user_name'];
$pw = $_SESSION['pw'];
?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>댓글창</title>
    <h3>댓글창</h3>

    <script type ="text/javascript">
    function reply_click(clicked_id)
    {
        alert(clicked_id);
    }
    </script>

</head>
<body>

<!--- 댓글 불러오기 -->
<div class = "container">
<div class="reply_view">
    <h3 style = "padding: 10px 0 15px 0; border-bottom: solid 1px grey;">댓글목록</h3>
    <?php
    $sql3= mq("SELECT * FROM comment WHERE board_id = '".$bid."' AND class = '0' AND deleted_flag = 'n' ORDER BY created desc");
    while($reply = $sql3 -> fetch_array()){
        $cid = $reply['comment_id'];
        $user_name = $reply['user_name'];
    ?>

    <div class = "comment view">
        <div style="background-color: beige"><b>ID:</b> <?=$reply['user_name']?></div>
        <div class = "comment edit" style="background-color: bisque"><b>COMMENT: </b><?php echo nl2br("$reply[content]");?></div>
        <div class = "comment date"><?=$reply['created']?>
            <?php
            if(isset($_SESSION['user_name']) && $_SESSION['user_name'] == $user_name){
            ?>
            <a class = "comment delete" href = "comment_delete.php?comment_id=<?=$cid?>" style="float: right"><button>삭제</button></a>
            <?php } ?>

        </div>


            <div style="text-indent: 50px;" class = "comment reply view">
                <?php
                $sql4= mq("SELECT * FROM comment WHERE board_id = '".$bid."' AND parent_id = '".$reply['comment_id']."' AND class ='1' AND deleted_flag = 'n' ORDER BY created asc");
                while($reply4 = $sql4 -> fetch_array()){
                    ?>
                    <div style="background-color: beige"><b>▶ID:</b> <?=$reply4['user_name']?></div>
                    <div class = "comment edit" style="background-color: bisque"><b>COMMENT: </b><?php echo nl2br("$reply4[content]");?></div>
                    <div class = "comment date"><?=$reply4['created']?>
                        <?php
                        if(isset($_SESSION['user_name']) && $_SESSION['user_name'] == $reply4['user_name']){
                        ?>
                        <a id="comment_reply_delete " class = "comment reply delete" href = "comment_delete.php?comment_id=<?=$reply4['comment_id'] ?>" style="float: right"><button>삭제</button></a>
                        <?php } ?>
                    </div>
                    <hr class="line">
                <?php } ?>

            </div>


        <div class ="comment_reply" id ="comment_reply" style="text-indent: 50px">
            <button class ="btn_comment_reply" id ="<?php echo $cid;?>">대댓글달기</button>
            <form action="process_re_comment.php?board_id=<?php echo $_GET['board_id']; ?>" method="post">
                <p class="comment_reply_content" id ="btn_content_id_<?php echo $cid;?>">
                    <input type="hidden" name="comment_id" id="comment_id" value=<?=$cid?>>
<!--                <input type="text" id="comment_reply_user_name" class="" name="user_name" size="15" placeholder="아이디" required>-->
<!--                <input type="password" id="comment_reply_pw" name="pw" size="15" placeholder="비밀번호" required>-->

                <p style="text-indent: 50px" class="comment_reply_content" id ="btn_content_id_<?php echo $cid;?>">
                    <textarea  name="content" placeholder="내용" required id ="content"></textarea>
                    <button id="rep_rep_bt">확인</button>
                </p>

                </p>
            </form>
        </div>

<!--            </form>-->
<!--        </div>-->

    <hr class="line">
    </div>

        <?php } ?>

</div>
</div>

<!-- 댓글 불러오기 done -->

<!--- 댓글 입력 폼 댓글인지 대댓글인지 class 도 보내야지-->
	<div class="dap_ins">
		<form action="process_comment.php?board_id=<?php echo $_GET['board_id']; ?>" method="post">
			<input type="hidden" name="bid" value=<?=$bid?>>
<!--            <input type="hidden" name="user_name" size="15" value=--><?//=$uid?><!-- >--->
<!--            <input type="hidden" name="pw" size="15" value=--><?//=$pw?><!-- >-->
<!--            <input type="text" name="user_name" size="15" placeholder="아이디" required>-->
<!--			<input type="password" name="pw" size="15" placeholder="비밀번호" required>-->
			<div style="margin-top:10px; ">
				<textarea name="content" placeholder="내용" required id ="content"></textarea>
				<button id="rep_bt" class="re_bt">댓글</button>
			</div>
		</form>
        <a id="go_back_to_view" href = "view.php.?board_id=<?=$bid ?>" style="float: left"><button>본문가기</button></a>

	</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("p").hide();
        $("button.btn_comment_reply").click(function(){
            console.log($(this).attr('id'));
            var btn_id = $(this).attr('id');
                $("p.comment_reply_content#btn_content_id_" +btn_id).toggle(700);
        });
    });
</script>

</html>