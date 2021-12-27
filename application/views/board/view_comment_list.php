<div id="comment_area">


    <table class="table table-striped">
        <tbody>
        <?php
        if (!empty($comment_list)) {
            foreach ($comment_list as $lt) {
                ?>
                <tr id="row_num_<?php echo $lt->comment_id; ?>" comment_id="<?= $lt->comment_id; ?>">
                    <th scope="row">
                        <?php echo $lt->login_id; ?>
                    </th>
                    <td><?php echo $lt->content; ?>
                    </td>
                    <td>
                        <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt->created)); ?>">
                            <?php echo $lt->created; ?>
                        </time>
                    </td>
                    <td>
                        <a href="#" id="comment_delete" comment_id="<?= $lt->comment_id; ?>">
                            <i class="icon-trash"></i>[삭제]
                        </a>
                        <a href="#" id="comment_reply" comment_reply_id="<?= $lt->comment_id; ?>" class="">
                            <i class="icon-trash"></i>[대댓글달기]
                        </a>
                    </td>

                </tr>
                <!--                    <div class="comment reply view" style="text-indent: 50px;">-->
                <!---->
                <!---->
                <!--                    </div>-->

                <tr>
                    <!--대댓글 인풋 부모인덱스-->


                    <td style="text-indent: 30px" class="comment_reply_content"
                        id="comment_reply_content_id_<?= $lt->comment_id; ?>">
                        <form class="form-horizontal" method="POST" action="" name="com_add2">
                            <div class="control-group2">
                                <label class="control-label2" for="input02">대댓글</label>
                                <div class="controls">
                                    <textarea class="input-xlarge2" id="input02" name="content" rows="3"></textarea>
                                    <input class="btn btn-primary2" type="button" id="comment_reply_add" value="작성"/>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                    </td>


                </tr>
                <?php
            }
        }
        ?>


        </tbody>
    </table>
