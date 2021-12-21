<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <title>CodeIgniter</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

</head>
<body>
<div id="main">
    <header id="header" data-role="header" data-position="fixed">
        <blockquote>
            <p>
                나스 보드
            </p>
            <small></small>
            <p>
                <?php
                if ( @$this -> session -> userdata('logged_in') == TRUE) {
                    ?>
                    <?php echo $this -> session -> userdata('login_id');?> 님 환영합니다. <a href="/auth/logout" class="btn">로그아웃</a>
                    <?php
                } else {
                    ?>
                    <a href="/auth/login" class="btn btn-primary"> 로그인 </a>
                    <?php
                }
                ?>
            </p>
        </blockquote>
    </header>
    <nav id="gnb">
        <ul>
            <li>
                <a rel="external" href="/board"> 게시판 프로젝트 </a>
            </li>
        </ul>
    </nav>
