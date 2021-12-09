<?php
//header("Content-Type: application/json");
//
//
//include $_SERVER['DOCUMENT_ROOT']."/db.php";
//$deleted_id = json_encode($_POST['aDel'], JSON_NUMERIC_CHECK);
//
////echo(json_encode(array("file_id" => $deleted_id)));
//
//$result = str_replace("[","", $deleted_id);
//$result2 = str_replace("]","", $result);
//
//$sql_1 = "
//UPDATE file SET deleted_flag = 'y' WHERE file_id IN ($result2)
//
//";
//echo $sql_1;
////$sql_file = mq("UPDATE file set deleted_flag = 'y' WHERE file_id = '$deleted_id';");
//mq($sql_1);