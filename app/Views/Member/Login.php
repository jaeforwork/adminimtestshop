<div class="container">
  <div class="row">
    <div class="col-md-12">
    <fieldset>
	    <legend><?=$PageTitle?></legend>
	    <?php echo $msg?>
	    <div class="form-group">
		    <label class="col-md-3 control-label" for="mb_id">아이디</label>
		    <div class="col-md-4">
			    <input type="text" id="mb_id" name="mb_id" class="form-control span3" maxlength="20" value="<?php echo $USER_ID?>" />
			    <div class="btn-group" data-toggle="buttons">
				    <label id="reId" class="btn btn-xs ">
					    <input type="checkbox" name="reId" value="1" />
					    <span class="glyphicon glyphicon-unchecked"></span> 아이디 저장하기
				    </label>
			    </div>
		    </div>
	    </div>
	    <div class="form-group">
		    <label class="col-md-3 control-label" for="mb_password">비밀번호</label>
		    <div class="col-md-4">
			    <input type="password" id="mb_password" name="mb_password" class="form-control span3" maxlength="20" />
			    <button type="submit" class="btn btn-primary">로그인</button>
		    </div>
	    </div>
	    <div class="form-group">
		  <label class="col-md-3 control-label"></label>
		    <div class="col-md-9">
			  <p>
				<span class="glyphicon glyphicon-exclamation-sign"></span> 아직 회원이 아니십니까?
				<a href="/Member/Join" class="btn btn-xs btn-info">회원가입</a>
			  </p>
			  <p>
				<span class="glyphicon glyphicon-question-sign"></span> 아이디/비밀번호를 잊으셨습니까?
				<a href="/Member/ForgetIdpwd" class="btn btn-xs btn-info">ID/비밀번호분실</a>
			  </p>
		    </div>
	    </div>
    </fieldset>
    </div>
  </div>
</div>
