<?php
//include $_SERVER['DOCUMENT_ROOT']."/db.php";
//
//$bid = $_GET['board_id'];
//
//$sql = mq("UPDATE board set deleted_flag = 'y' WHERE board_id='$bid';");
//?>
<!--<script type="text/javascript">alert("삭제되었습니다.");</script>-->
<!--<meta http-equiv="refresh" content="0 url=/" />-->


<html>
<head>
    <title>삭제</title>
</head>

<body>
<?php
include $_SERVER['DOCUMENT_ROOT']."/db.php";
$bid = $_GET['board_id'];
?>

<center>
    <BR>

    <!-- 입력된 값을 다음 페이지로 넘기기 위해 FORM을 만든다. -->
    <form action=delete_ok.php?board_id=<?=$_GET['board_id']?> method=post>

        <table width=300 border=0 cellpadding=2 cellspacing=1 bgcolor=#777777>
            <tr>
                <td height=20 align=center bgcolor=#999999>
                    <font color=white><B>비 밀 번 호 확 인</B></font>
                </td>
            </tr>
            <tr>
                <td align=center>
                    <font color=white><B>비밀번호 : </B>
                        <INPUT type=password name=pw size=8 maxlength=8>
                        <INPUT type=submit value="확 인">
                </td>
            </tr>
        </table>
    </form>

</center>
</body>
</html>