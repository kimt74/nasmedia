<!DOCTYPE>

<html>
<head>
    <meta charset='utf-8'>
</head>

<body>
<div style="text-align: center">
    <span>로그인</span>

    <form method="POST" action="process_login.php">
        <p>ID: <input name="user_name" type="text" required></p>
        <p>PW: <input name="pw" type="password" required></p>
        <input type="submit" value="로그인">
    </form>
    <br />
    <button id="join" onclick="location.href='./join.php'">회원가입</button>

</div>


</body>

</html>
