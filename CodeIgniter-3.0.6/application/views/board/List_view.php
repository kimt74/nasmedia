<article id="board_area">
    <header>
        <h1></h1>
    </header>
    <h1></h1>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th scope="col">번호</th>
            <th scope="col">제목</th>
            <th scope="col">작성자</th>
            <th scope="col">작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php

        if (isset($list)) {
            foreach($list as $lt)
            {
//                var_dump($this -> uri -> segment(1));
                ?>
                <tr>
                    <th scope="row"><?php echo $lt -> board_id;?></th>
                    <td><a rel="external" href="<?php echo $this -> uri -> segment(1); ?>/view/<?php echo $this -> uri -> segment(3); ?>/<?php echo $lt -> board_id; ?>"> <?php echo $lt -> title;?></a></td>
                    <td><?php echo $lt -> user_id;?></td>
                    <td>
                        <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt -> created)); ?>">
                            <?php echo mdate("%Y-%M-%j", human_to_unix($lt -> created));?>
                        </time></td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="5"><?php if (!empty($pagination)) {
                    echo $pagination;
                } ?></th>
        </tr>
        </tfoot>



    </table>
</article>