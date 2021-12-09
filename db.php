<?php

header('Content-Type: text/html; charset=utf-8'); // utf-8인코딩

// localhost = DB주소, web=DB계정아이디, 1234=DB계정비밀번호, post_board="DB이름"
$db = new mysqli("localhost","root","autoset","myboard"); //db 변수에 mysql 연결
$db->set_charset("utf8"); //utf8 인코딩

function mq($sql)
{
    global $db; // $db를 전역변수로
    return $db->query($sql);
}
