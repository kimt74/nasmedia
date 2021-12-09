<?
header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = invoice.xls" );
header( "Content-Description: PHP4 Generated Data" );
?>

<?
$conn = mysqli_connect(
    'localhost',
    'root',
    'autoset',
    'myboard');
if(isset($_GET['board_id'])) //id 가 존재하는지 체크
{
    $filtered_id = mysqli_real_escape_string($conn, $_GET['board_id']);//sql injection 체크
    $sql = "SELECT * FROM board WHERE board_id ={$filtered_id}";

    $result = mysqli_query($conn,$sql); // sql에 가져온 정보를 result에 담는다.
// 테이블 상단 만들기
    $EXCEL_STR = "
<table border='1'>
<tr>
   <td>게시물 아이디</td>
   <td>제목</td>
   <td>내용</td>
   <td>작성자</td>
   <td>업로드한 파일수</td>
   <td>작성 날짜</td>
</tr>";
}
//위에 talbe은 자신이 가져올 값들의 컬럼 명이 되겠다.
while($row = mysqli_fetch_array($result)) {
    $EXCEL_STR .= "
   <tr>
   <td>".$row['board_id']."</td>
   <td>".$row['title']."</td>
   <td>".$row['content']."</td>
   <td>".$row['user_name']."</td>
   <td>".$row['file_count']."</td>
   <td>".$row['created']."</td>
    
    </tr>
   ";
}
$EXCEL_STR .= "</table>";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'> ";
echo $EXCEL_STR;
?>
