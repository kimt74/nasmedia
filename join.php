<!--<!DOCTYPE>-->
<!---->
<!--<html>-->
<!--<head>-->
<!--    <meta charset='utf-8'>-->
<!--</head>-->
<!--<body>-->
<!---->
<!--<div align="center">-->
<!--    <p>회원가입</p>-->
<!--    <form method='POST' action='process_join.php'>-->
<!--        <p>ID: <input type="text" name="user_name" required></p>-->
<!--        <p>PW: <input type="password" name="pw" required></p>-->
<!--        <p>Email: <input type="email" name="email" required></p>-->
<!--        <input type="submit" value="회원가입">-->
<!--    </form>-->
<!--</div>-->
<!--</body>-->
<!--</html>-->
<?php include $_SERVER['DOCUMENT_ROOT']."/db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>회원가입</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(e) {
            $(".check").on("keyup", function(){ //check라는 클래스에 입력을 감지
                let self = $(this);
                let user_name;

                if(self.attr("id") === "user_name"){
                    user_name = self.val();
                }

                $.post( //post방식으로 id_check.php에 입력한 userid값을 넘깁니다
                    "id_check.php",
                    { user_name : user_name },
                    function(data){
                        if(data){ //만약 data값이 전송되면
                            self.parent().parent().find("div").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
                            self.parent().parent().find("div").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
                        }
                    }
                );
            });
        });
    </script>
</head>
<body>
<form method="post" action="process_join.php" name="join_form">
    <h1>회원가입</h1>
    <fieldset>
        <legend>입력사항</legend>
        <table>
            <tr>
                <td>아이디</td>
                <td><input type="text" size="35" name="user_name" id="user_name" class="check" placeholder="아이디"  required />
                <td><div id="id_check">아이디가 실시간으로 검사됩니다</div></td>
                </td>

            </tr>
            <tr>
                <td>비밀번호</td>
                <td><input type="password" size="35" name="pw" placeholder="비밀번호" required></td>
            </tr>
            <tr>
                <td>이메일</td>
                <td><input type="text" name="email" required>@<select name="emadress"><option value="naver.com">naver.com</option><option value="nate.com">nate.com</option>
                        <option value="gmail.com">gmail.com</option></select></td>
            </tr>
        </table>
        <input type="submit" value="가입하기" /><input type="reset" value="다시쓰기" />
    </fieldset>
</form>
</body>
</html>