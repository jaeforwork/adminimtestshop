<div class="MemberModifyWrap">
	<?=$LMenuName?>
	<div class="ContentsWrap">
		<form id="fmodify" class="form-horizontal" name="fmodify" method="post" action="<?=RT_PATH?>/Member/Modify/Update" enctype="multipart/form-data">
			<input type="hidden" name="w"		value="u" />
			<input type="hidden" name="mb_id"   value="<?=$mb_id?>" />
			<input type="hidden" name="mb_name" value="<?=$mb_name?>" />
			<input type="hidden" name="token"   value="<?=$token?>" />
			<? if (!$open_modify): ?>
			<input type="hidden" name="mb_open" value="<?=$mb_open?>" />
		<? endif; ?>

		<?=validation_errors('<pre>','</pre>')?>
		<fieldset>
			<legend><?=$title?></legend>
			<div class="form-group">
				<label class="col-md-3 control-label">아이디</label>
				<div class="col-md-4">
					<p class="form-control-static text-primary"><strong><?=$mb_id?></strong></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="mb_password_q"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호 분실시 질문</label>
				<div class="col-md-4">
					<select name="mb_password_q_select" class="form-control" onchange="this.form.mb_password_q.value=this.value;">
						<option value="">선택하세요</option>
						<option value="내가 좋아하는 캐릭터는?">내가 좋아하는 캐릭터는?</option>
						<option value="타인이 모르는 자신만의 신체비밀이 있다면?">타인이 모르는 자신만의 신체비밀이 있다면?</option>
						<option value="자신의 인생 좌우명은?">자신의 인생 좌우명은?</option>
						<option value="초등학교 때 기억에 남는 짝꿍 이름은?">초등학교 때 기억에 남는 짝꿍 이름은?</option>
						<option value="유년시절 가장 생각나는 친구 이름은?">유년시절 가장 생각나는 친구 이름은?</option>
						<option value="가장 기억에 남는 선생님 성함은?">가장 기억에 남는 선생님 성함은?</option>
						<option value="친구들에게 공개하지 않은 어릴 적 별명이 있다면?">친구들에게 공개하지 않은 어릴 적 별명이 있다면?</option>
						<option value="다시 태어나면 되고 싶은 것은?">다시 태어나면 되고 싶은 것은?</option>
						<option value="가장 감명깊게 본 영화는?">가장 감명깊게 본 영화는?</option>
						<option value="읽은 책 중에서 좋아하는 구절이 있다면?">읽은 책 중에서 좋아하는 구절이 있다면?</option>
						<option value="기억에 남는 추억의 장소는?">기억에 남는 추억의 장소는?</option>
						<option value="인상 깊게 읽은 책 이름은?">인상 깊게 읽은 책 이름은?</option>
						<option value="자신의 보물 제1호는?">자신의 보물 제1호는?</option>
						<option value="받았던 선물 중 기억에 남는 독특한 선물은?">받았던 선물 중 기억에 남는 독특한 선물은?</option>
						<option value="자신이 두번째로 존경하는 인물은?">자신이 두번째로 존경하는 인물은?</option>
						<option value="아버지의 성함은?">아버지의 성함은?</option>
						<option value="어머니의 성함은?">어머니의 성함은?</option>
					</select>
					<span class="help-block"><input type="text" id="mb_password_q" name="mb_password_q" class="form-control" maxlength="50" value="<?=$mb_password_q?>" /></span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label" for="mb_password_a"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호 분실시 답변</label>
				<div class="col-md-4">
					<input type="text" id="mb_password_a" name="mb_password_a" class="form-control span4" maxlength="50" value="<?=$mb_password_a?>" />
					<a href="<?=RT_PATH?>/Member/Modify/Password" class="btn btn-link">[ 비밀번호 변경 ]</a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">이름</label>
				<div class="col-md-4">
					<p class="form-control-static text-primary"><strong><?=$mb_name?></strong></p>
				</div>
			</div>

			<? if ($cf_use_nick): ?>
			<div class="form-group">
				<label class="col-md-3 control-label" for="reg_mb_nick"><span class="glyphicon glyphicon-exclamation-sign"></span> 별명</label>
				<div class="col-md-4">
					<? if ($nick_modify): ?>
					<input type="hidden" name="mb_nick_default" value="<?=$mb_nick?>" />
					<input type="text" id="reg_mb_nick" name="mb_nick" class="form-control span3" maxlength="20" value="<?=$mb_nick?>" />
					<button type="button" id="btn_nick" class="btn btn-info" data-loading-text="확인 중...">중복확인</button>
					<span id="msg_mb_nick"></span>
					<span class="help-block">
						공백없이 한글, 영문, 숫자만 입력 가능 (한글2자, 영문4자 이상)
						<br/>별명을 바꾸시면 앞으로 <?=$cf_nick_modify?>일 이내에는 변경할 수 없습니다.
					</span>

				<? else: ?>
				<span class="text-primary"><strong><?=$mb_nick?></strong></span>
				<input type="hidden" name="mb_nick_default" value="<?=$mb_nick?>" />
				<input type="hidden" name="mb_nick" value="<?=$mb_nick?>" />
			<? endif; ?>
		</div>
	</div>
<? endif; ?>

<div class="form-group">
	<label class="col-md-3 control-label" for="reg_mb_email"><span class="glyphicon glyphicon-exclamation-sign"></span> 이메일</label>
	<div class="col-md-4">
		<input type="hidden" name="old_email" value="<?=$mb_email?>" />
		<div class="input-group">
			<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
			<input type="email" id="reg_mb_email" name="mb_email" class="form-control" maxlength="50" value="<?=$mb_email?>" />		
		</div>
	</div>
	<div class="col-md-3">
		<button type="button" id="btn_email" class="btn btn-info" data-loading-text="확인 중...">중복확인</button>
		<span id="msg_mb_email"></span>
	</div>
</div>

	<? /* if ($mb_birth == "0000-00-00"): ?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_birth">생년월일</label>
		<div class="col-md-3">
			<div class="input-group">
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
				<input type="text" id="mb_birth" name="mb_birth" class="form-control" maxlength="10" value="<?=$mb_birth?>" readonly="readonly" />	
			</div>
		</div>
		<div class="col-md-6">입력시 수정불가</div>
	</div>
	<? endif; ?>

	<? if (!$mb_sex): ?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_sex">성별</label>
		<div class="col-md-9">
			<select id="mb_sex" name="mb_sex" class="form-control span2">
				<option value="">선택</option>
				<option value="M">남자</option>
				<option value="F">여자</option>
			</select> 입력시 수정불가
		</div>
	</div>
	<? endif; ?>

	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_homepage">홈페이지</label>
		<div class="col-md-4">
			<div class="input-group">
				<span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
				<input type="url" id="mb_homepage" name="mb_homepage" class="form-control" maxlength="40" value="<?=$mb_homepage?>" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_tel">전화번호</label>
		<div class="col-md-3">
			<input type="tel" id="mb_tel" name="mb_tel" class="form-control" maxlength="14" value="<?=$mb_tel?>" />
		</div>
	</div>
	*/?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_hp">휴대폰번호</label>
		<div class="col-md-3">
			<input type="tel" id="mb_hp" name="mb_hp" class="form-control" maxlength="14" value="<?=$mb_hp?>" />
		</div>
	</div>
	<?/*
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_addr2">주소</label>
		<div class="col-md-5">
			<input type="text" name="mb_zip1" class="form-control span2" maxlength="3" readonly="readonly" value="<?=$mb_zip1?>" /> -
			<input type="text" name="mb_zip2" class="form-control span2" maxlength="3" readonly="readonly" value="<?=$mb_zip2?>" />
			<button type="button" class="btn btn-info" onclick="win_zip('fmodify','mb_zip1','mb_zip2','mb_addr1','mb_addr2');">우편번호검색</button>
			<span class="help-block"><input type="text" name="mb_addr1" class="form-control" maxlength="100" readonly="readonly" value="<?=$mb_addr1?>" /></span>
			<span class="help-block"><input type="text" id="mb_addr2" name="mb_addr2" class="form-control" maxlength="100" value="<?=$mb_addr2?>" /></span>
		</div>
	</div>
	*/?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_profile">자기소개</label>
		<div class="col-md-5">
			<textarea id="mb_profile" name="mb_profile" class="form-control" rows="3"><?=$mb_profile?></textarea>
		</div>
	</div>

	<? if ($cf_icon_size): ?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_icon">회원아이콘</label>
		<div class="col-md-5">
			<div class="input-group">
				<span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
				<input type="file" id="mb_icon" name="mb_icon" class="form-control" />
			</div>
			<span class="help-block">
				<p>아이콘 크기는 가로(<?=$cf_icon_width?>픽셀) x 세로(<?=$cf_icon_height?>픽셀) 이하로 해주세요.
					<br/>( gif 이미지만 가능 / 용량 : <?=$cf_icon_size?>Kbyte 이하만 등록됩니다. )</p>

					<? if ($mb_icon): ?>
					<p><img src="<?=$mb_icon?>" align="top" alt="회원아이콘" />
						<label class="checkbox-inline"><input type="checkbox" name="del_mb_icon" value="1" /> 삭제</label></p>
					<? endif; ?>
				</span>
			</div>
		</div>
	<? endif; ?>

	<? if ($cf_named_size): ?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_named">이미지이름</label>
		<div class="col-md-5">
			<div class="input-group">
				<span class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></span>
				<input type="file" id="mb_named" name="mb_named" class="form-control" />
			</div>
			<span class="help-block">
				<p>이미지 크기는 가로(<?=$cf_named_width?>픽셀) x 세로(<?=$cf_named_height?>픽셀) 이하로 해주세요.
					<br/>( gif 이미지만 가능 / 용량 : <?=$cf_named_size?>Kbyte 이하만 등록됩니다. )</p>
					
					<? if ($mb_named): ?>
					<p><img src="<?=$mb_named?>" align="top" alt="이미지이름" />
						<label class="checkbox-inline"><input type="checkbox" name="del_mb_named" value="1" /> 삭제</label></p>
					<? endif; ?>
				</span>
			</div>
		</div>
	<? endif; ?>

	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_mailling">이메일 수신 동의</label>
		<div class="col-md-4">
			<div class="btn-group" data-toggle="buttons">
				<label id="mb_mailling" class="btn btn-sm btn-default">
					<input type="checkbox" name="mb_mailling" value="Y" />
					<span class="glyphicon glyphicon-unchecked"></span> 정보 이메일을 받겠습니다.
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_sms">SMS 수신동의</label>
		<div class="col-md-4">
			<div class="btn-group" data-toggle="buttons">
				<label id="mb_sms" class="btn btn-sm btn-default">
					<input type="checkbox" name="mb_sms" value="Y" />
					<span class="glyphicon glyphicon-unchecked"></span> 휴대전화 문자로 받겠습니다.
				</label>
			</div>
		</div>
	</div>
	
	<? if ($open_modify): ?>
	<div class="form-group">
		<label class="col-md-3 control-label" for="mb_open">정보공개</label>
		<div class="col-md-5">
			<input type="hidden" name="mb_open_default" value="<?=$mb_open?>" />
			<div class="btn-group" data-toggle="buttons">
				<label id="mb_open" class="btn btn-sm btn-default">
					<input type="checkbox" name="mb_open" value="Y" />
					<span class="glyphicon glyphicon-unchecked"></span> 다른분들이 나의 정보를 볼 수 있도록 합니다.
				</label>
			</div>
			<span class="help-block">정보공개를 바꾸시면 앞으로 <?=$cf_open_modify?>일 이내에는 변경이 안됩니다.</span>
		</div>
	</div>
<? endif; ?>

<div class="form-group">
	<label class="col-md-3 control-label" for="wr_key"><span class="glyphicon glyphicon-exclamation-sign"></span> 자동등록방지</label>
	<div class="col-md-7">
		<img src="<?=IMG_DIR?>/js/load_kcaptcha.gif" id="kcaptcha" class="img-rounded" width="100" height="50" alt="자동등록방지" />
		<input type="text" id="wr_key" name="wr_key" class="form-control span3" /> 이미지의 글자를 입력하세요.
		<span class="help-block">글자가 잘 안 보일 때 이미지를 클릭하면 새로운 글자가 나옵니다.</span>
	</div>
</div>

<hr />

<p class="text-center">
	<button type="submit" class="btn btn-lg btn-success">수정</button>
</p>
</fieldset>

</form>
</div>

<script type='text/javascript' src='<?=JS_DIR?>/md5.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/kcaptcha.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_ext.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_reg.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/datepicker.js'></script>
<script type='text/javascript'>
//<![CDATA[
$('#mb_mailling').checkicon(<?=$mail_chk?>);
$('#mb_open').checkicon(<?=$open_chk?>);
$('#mb_sms').checkicon(<?=$open_chk?>);

$(function() {
	var year = new Date().getFullYear();
	$('#mb_birth').datepicker({yearRange:(year-60)+':'+year});

	$('#fmodify').validate({
		onkeyup: false,
		rules: {
			mb_birth: { minlength:10 },
			mb_nick: { required:true, reg_mb_nick:true },
			mb_email: { required:true, reg_mb_email:true },
			mb_password_q: 'required',
			mb_password_a: 'required',
			mb_icon : { accept:'image/gif' },
			mb_named : { accept:'image/gif' },
			wr_key: { required:true, wrKey:true }
		},
		messages: {
			mb_birth: '올바른 형식이 아닙니다.',		  
			mb_nick: '별명 중복확인 결과가 올바르지 않습니다.',
			mb_email: '이메일 중복확인 결과가 올바르지 않습니다.',
			mb_password_q: '비밀번호 분실시 질문을 선택하거나 입력하세요.',
			mb_password_a: '비밀번호 분실시 답변을 입력하세요.',
			mb_icon : '파일이 gif 이미지가 아닙니다.',
			mb_named : '파일이 gif 이미지가 아닙니다.',
			wr_key: '자동등록방지용 코드가 맞지 않습니다.'
		}
	});
});
//]]>
</script>