<article id="board_area">
    <header><h1></h1></header>
    <?php
    $attributes = array(
        'class' => 'form-horizontal',
        'id' => 'auth_login'
    );
    echo form_open('/Auth/login', $attributes);
    ?>
    <fieldset>
        <legend>로그인</legend>
        <div class="control-group">
            <label class="control-label" for="input1">아이디</label>
            <div class="controls">
                <input type="text" class="input-xlarge" id="input1" name="login_id"
                       value="<?php echo set_value('login_id'); ?>" />
                <p class="help-block"></p>
            </div>
            <label class="control-label" for="input2">비밀번호</label>
            <div class="controls">
                <input type="password" class="input-xlarge" id="input2" name="pw"
                       value="<?php echo set_value('pw'); ?>" />
                <p class="help-block"></p>
            </div>
            <div class="controls">
                <p class="help-block"><?php echo validation_errors();?></p>
            </div>

            <div class="form_actions">
                <button type="submit" class="btn btn-primary">확인</button>
                <button class="btn" onclick="document.location.reload()">취소</button>
            </div>
        </div>
    </fieldset>
</article>