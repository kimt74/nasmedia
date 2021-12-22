
    <script>
        $(document).ready(function () {
            $("#search_btn").click(function () {
                if ($("#q").val() == '') {
                    alert("검색어를 입력하세요!");
                    return false;
                } else {
                    // var act = "/board/lists/board/q/" + $("#q").val() + "/page/1";
                    var act = "/board/?search_word=" + $("#q").val() + "&per_page=1";
                    $("#bd_search").attr('action', act).submit();
                }
            });
        });

        function board_search_enter(form) {
            var keycode = window.event.keyCode;
            if (keycode == 13)
                $("#search_btn").click();
        }
    </script>
</head>


<article id="board_area">
    <header>
        <h1></h1>
    </header>
    <h1></h1>
    <table cellpadding="0" cellspacing="0" border="1" width="0">
        <thead>
        <tr>
            <th scope="col">번호</th>
            <th scope="col">제목</th>
            <th scope="col">작성자</th>
            <th scope="col">조회수</th>
            <th scope="col">작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($list)) {
            foreach ($list as $lt) {
//                var_dump($this -> uri -> segment(1));
                ?>
                <tr>
                    <th scope="row"><?= $lt->board_id; ?></th>
                    <td>
                        <!--
                        <a rel="external"
                           href="ci/?<?= $this->uri->segment(1); ?>/view/<?= $lt->board_id; ?>"> <?= $lt->title; ?>
                        </a>
                        -->
                        <a rel="external" href="/board/view?id=<?= $lt->board_id; ?>"> <?= $lt->title; ?>
                        </a>

                    </td>
                    <td><?= $lt->login_id; ?></td>
                    <td><?= $lt->hits; ?></td>
                    <td>
                        <time datetime="<?= mdate("%Y-%M-%j", human_to_unix($lt->created)); ?>">
                            <?= mdate("%Y-%M-%j", human_to_unix($lt->created)); ?>
                        </time>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="5">
                <?php if (!empty($pagination)) {
                    echo $pagination;
                } ?>
            </th>
        </tr>
        </tfoot>
    </table>
    <div>
        <form id="bd_search" method="post">
            <input type="text" name="search_word" id="q" onkeypress="board_search_enter(document.q);"/>
            <input type="button" value="검색" id="search_btn"/>
        </form>
    </div>
    <div>
            <a href="/board/write" class="btn btn-primary">[게시물등록]</a>
    </div>
</article>

</html>
