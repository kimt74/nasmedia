<div>
    <div class="span4"></div>
    <div class="span4">
        <?php echo validation_errors(); ?>
        <form class="form-horizontal" action="/index.php/auth/register" method="post">
            <div class="control-group">
                <label class="control-label" for="inputEmail">이메일</label>
                <div class="controls">
                    <input type="text" id="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="이메일">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="login_id">로그인 ID</label>
                <div class="controls">
                    <input type="text" id="login_id" name="login_id" value="<?php echo set_value('login_id'); ?>"  placeholder="닉네임">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="pw">비밀번호</label>
                <div class="controls">
                    <input type="password" id="pw" name="pw" value="<?php echo set_value('pw'); ?>"   placeholder="비밀번호">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="re_pw">비밀번호 확인</label>
                <div class="controls">
                    <input type="password" id="re_pw" name="re_pw" value="<?php echo set_value('re_pw'); ?>"   placeholder="비밀번호 확인">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"></label>
                <div class="controls">
                    <input type="submit" class="btn btn-primary" value="회원가입" />
                </div>
            </div>
        </form>
    </div>
    <div class="span4"></div>
</div>