    <article id="board_area">
        <header>
            <h1></h1>
        </header>
        <table cellspacing="0" cellpadding="0" class="table table-striped" border="1">
            <thead>
            <tr>
                <th scope="col">제목: <?php if (isset($views)) {
                        echo $views -> title;
                    } ?></th>
                <th scope="col">이름: <?php echo $views -> login_id;?></th>
                <th scope="col">조회수: <?php echo $views -> hits;?></th>
                <th scope="col">등록일: <?php echo $views -> created;?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th colspan="4">
                    <?php echo $views -> content;?>
                </th>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4">
<!--                    <a href="/board/lists/--><?php //echo $this -> uri -> segment(3); ?><!--/-->
<!--                                    page/--><?php //echo $this -> uri -> segment(7); ?><!--" class="btn btn-primary">목록 </a>-->
                    <a href="javascript:history.back();" class="btn btn-primary">목록</a>
                    <a href="/board/modify?id=<?=$this->input->get('id');?>"
                       class="btn btn-warning"> 수정 </a>
                    <a href="/board/delete?id=<?= $this -> input->get('id');?>"
                       class="btn btn-danger"> 삭제 </a>
                    <a href="/board/write/<?php echo $this -> uri -> segment(3); ?>/page/<?php echo $this -> uri -> segment(7); ?>"
                       class="btn btn-success">쓰기</a>
                </th>
            </tr>
            </tfoot>
        </table>
    </article>
    <footer id="footer">
        <dl>
            <dt>
            </dt>
            <dd>
            </dd>
        </dl>
    </footer>
</div>
</body>
</html>


