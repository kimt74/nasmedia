<div id="comment_area">
    <table class="table table-striped" border="1">
        <tbody>
        <?php
        if (!empty($comment_list)) {
            foreach ($comment_list as $lt) {
                if (($lt->parent_id) === '0') {
                    ?>
                    <tr style="background-color: beige" id="row_num_<?php echo $lt->comment_id; ?>"
                        comment_id="<?= $lt->comment_id; ?>">
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
                            <a href="javascript:; " id="comment_delete" comment_id="<?= $lt->comment_id; ?>">
                                <i class="icon-trash"></i>[삭제]
                            </a>
                            <a href="javascript:; " id="comment_reply" comment_reply_id="<?= $lt->comment_id; ?>"
                               class="">
                                <i class="icon-trash"></i>[대댓글달기]
                            </a>

                        </td>

                    </tr>
                    <!--대댓글 뷰-->

                    <?php
                    foreach ($comment_list as $lt2) {
                        if (($lt->comment_id) === ($lt2->parent_id)) {


                            ?>
                            <tr style="text-indent: 50px" id="row_num_<?php echo $lt2->comment_id; ?>"
                                comment_id="<?= $lt2->comment_id; ?>">
                                <th scope="row">
                                    ⇨ <?php echo $lt2->login_id; ?>
                                </th>

                                <td><?php echo $lt2->content; ?>
                                </td>
                                <td>
                                    <time datetime="<?php echo mdate("%Y-%M-%j", human_to_unix($lt2->created)); ?>">
                                        <?php echo $lt2->created; ?>
                                    </time>
                                </td>
                                <td>
                                    <a href="javascript:; " id="comment_delete" comment_id="<?= $lt2->comment_id; ?>">
                                        <i class="icon-trash"></i>[삭제]
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>


                    <tr>
                        <!--대댓글 인풋 부모인덱스-->
                        <td class="comment_reply_content" id="comment_reply_content_id_<?= $lt->comment_id; ?>">
                            <form class="form-horizontal" method="POST" action="" name="com_add">
                                <div class="control-group2">
                                    <label class="control-label2" for="input_for_comment_reply">대댓글</label>
                                    <div class="controls" id="btn_comment_reply_content_id_<?= $lt->comment_id; ?>">
                                        <textarea class="input-xlarge" id="input_for_comment_reply" name="content" rows="3"
                                                  required></textarea>
                                        <input class="btn btn-primary" type="button" id="comment_reply_add" value="작성"/>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>


        </tbody>
    </table>
</div>
