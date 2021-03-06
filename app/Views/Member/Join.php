
<form id="fjoin" class="form-horizontal" name="fjoin" method="post" action="<?=RT_PATH?>/Member/Join/Update">
    
<input type="hidden" name="token" value="<?=$token?>" />

<?=validation_errors('<pre>','</pre>')?>
<fieldset>
    <legend><?=$title?></legend>
    <div class="form-group">
        <label class="col-md-3 control-label" for="reg_mb_id"><span class="glyphicon glyphicon-exclamation-sign"></span> 아이디</label>
        <div class="col-md-9">
            <input id="reg_mb_id" name="mb_id" class="form-control span3" maxlength="20" />
            <button type="button" id="btn_id" class="btn btn-info" data-loading-text="확인 중...">중복확인</button>
            <span id="msg_mb_id"></span>
            <span class="help-block">※ 영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label" for="mb_password"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호</label>
        <div class="col-md-9">
            <input type="password" id="mb_password" name="mb_password" class="form-control span3" maxlength="20" /> 3 ~ 20자
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label" for="mb_password_re"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호 확인</label>
        <div class="col-md-9">
            <input type="password" id="mb_password_re" name="mb_password_re" class="form-control span3" maxlength="20" />
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
            <span class="help-block"><input type="text" id="mb_password_q" name="mb_password_q" class="form-control" maxlength="50" /></span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label" for="mb_password_a"><span class="glyphicon glyphicon-exclamation-sign"></span> 비밀번호 분실시 답변</label>
        <div class="col-md-4">
            <input type="text" id="mb_password_a" name="mb_password_a" class="form-control" maxlength="50" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label" for="mb_name"><span class="glyphicon glyphicon-exclamation-sign"></span> 이름</label>
        <div class="col-md-9">
            <input type="text" id="mb_name" name="mb_name" class="form-control span2" maxlength="10" value="<?=$mb_name?>" />
            공백없이 한글만 입력 가능
        </div>
    </div>

    <? if ($cf_use_nick): ?>
    <div class="form-group">
        <label class="col-md-3 control-label" for="reg_mb_nick"><span class="glyphicon glyphicon-exclamation-sign"></span> 별명</label>
        <div class="col-md-9">
            <input type="text" id="reg_mb_nick" name="mb_nick" class="form-control span3" maxlength="20" />
            <button type="button" id="btn_nick" class="btn btn-info" data-loading-text="확인 중...">중복확인</button>
            <span id="msg_mb_nick"></span>
            <span class="help-block">
                공백없이 한글, 영문, 숫자만 입력 가능 (한글2자, 영문4자 이상)
                <br/>별명을 바꾸시면 앞으로 <?=$cf_nick_modify?>일 이내에는 변경할 수 없습니다.
            </span>
        </div>
    </div>
    <? endif; ?>

    <div class="form-group">
        <label class="col-md-3 control-label" for="reg_mb_email"><span class="glyphicon glyphicon-exclamation-sign"></span> 이메일</label>
        <div class="col-md-4">
            <div class="input-group">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                <input type="email" id="reg_mb_email" name="mb_email" class="form-control" maxlength="50" />
            </div>
        </div>
        <div class="col-md-5">
            <button type="button" id="btn_email" class="btn btn-info" data-loading-text="확인 중...">중복확인</button>
            <span id="msg_mb_email"></span>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-9">
            <button type="button" class="btn btn-sm btn-link" onclick="$('#joinOption').toggle();">
                <span class="glyphicon glyphicon-info-sign"></span> 더 많은 회원정보를 입력하시려면 클릭하세요.
            </button>
        </div>
    </div>

    <div id="joinOption" style="display:none;">
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_birth">생년월일</label>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" id="mb_birth" name="mb_birth" class="form-control" maxlength="10" readonly="readonly" />
                </div>
            </div>
            <div class="col-md-6">입력시 수정불가</div>
        </div>
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
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_homepage">홈페이지</label>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
                    <input type="url" id="mb_homepage" name="mb_homepage" class="form-control" maxlength="40" />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_tel">전화번호</label>
            <div class="col-md-3">
                <input type="tel" id="mb_tel" name="mb_tel" class="form-control" maxlength="14" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_hp">휴대폰번호</label>
            <div class="col-md-3">
                <input type="tel" id="mb_hp" name="mb_hp" class="form-control" maxlength="14" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_addr2">주소</label>
            <div class="col-md-5">
                <input type="text" name="mb_zip1" class="form-control span2" maxlength="3" readonly="readonly" /> -
                <input type="text" name="mb_zip2" class="form-control span2" maxlength="3" readonly="readonly" />
                <button type="button" class="btn btn-info" onclick="win_zip('fjoin','mb_zip1','mb_zip2','mb_addr1','mb_addr2');">우편번호검색</button>
                <span class="help-block"><input type="text" name="mb_addr1" class="form-control" maxlength="100" readonly="readonly" /></span>
                <span class="help-block"><input type="text" id="mb_addr2" name="mb_addr2" class="form-control" maxlength="100" /></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_profile">자기소개</label>
            <div class="col-md-5">
                <textarea id="mb_profile" name="mb_profile" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_mailling">메일링 서비스</label>
            <div class="col-md-9">
                <div class="btn-group" data-toggle="buttons">
                    <label id="mb_mailling" class="btn btn-sm btn-default">
                        <input type="checkbox" name="mb_mailling" value="1" />
                        <span class="glyphicon glyphicon-unchecked"></span> 정보 메일을 받겠습니다.
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label" for="mb_open">정보공개</label>
            <div class="col-md-9">
                <div class="btn-group" data-toggle="buttons">
                    <label id="mb_open" class="btn btn-sm btn-default">
                        <input type="checkbox" name="mb_open" value="1" />
                        <span class="glyphicon glyphicon-unchecked"></span> 다른분들이 나의 정보를 볼 수 있도록 합니다.
                    </label>
                </div>
                <span class="help-block">정보공개를 바꾸시면 앞으로 <?=$cf_open_modify?>일 이내에는 변경이 안됩니다.</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="wr_key"><span class="glyphicon glyphicon-exclamation-sign"></span> 자동등록방지</label>
        <div class="col-md-9">
            <img src="<?=IMG_DIR?>/js/load_kcaptcha.gif" id="kcaptcha" class="img-rounded" width="100" height="50" alt="자동등록방지" />     
            
            <input type="text" id="wr_key" name="wr_key" class="form-control span2" /> 이미지의 글자를 입력하세요.
            <span class="help-block">글자가 잘 안 보일 때 이미지를 클릭하면 새로운 글자가 나옵니다.</span>
        </div>
        

    </div>

    <hr />
    
    <p class="text-center">
        <button type="submit" class="btn btn-lg btn-success">가입</button>
    </p>
</fieldset>

</form>


<script type='text/javascript' src='<?=JS_DIR?>/md5.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/kcaptcha.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_ext.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/validate_reg.js'></script>
<script type='text/javascript' src='<?=JS_DIR?>/jquery/datepicker.js'></script>
<script type='text/javascript'>
//<![CDATA[
$('#mb_mailling,#mb_open').checkicon(1);

$(function() {
    var year = new Date().getFullYear();
    $('#mb_birth').datepicker({yearRange:(year-60)+':'+year});

    $('#fjoin').validate({
        onkeyup: false,
        rules: {
            mb_id: { required:true, reg_mb_id:true },
            mb_nick: { required:true, reg_mb_nick:true },
            mb_email: { required:true, reg_mb_email:true },
            mb_password: { required:true, minlength:3 },
            mb_password_re: { required:true, equalTo:'#mb_password'},
            mb_password_q: 'required',
            mb_password_a: 'required',
            mb_name: { required:true,  minlength:2, hangul:true },
            mb_birth: { minlength:10 },
            /*mb_profile: { required: function(element) {
                    return 0 > (<?=$todays?> - parseInt($('#mb_birth').val()) - 140000);
                }
            },*/
            wr_key: { required:true, wrKey:true }
        },
        messages: {
            mb_id: '아이디 확인 결과가 올바르지 않습니다.',
            mb_nick: '별명 확인 결과가 올바르지 않습니다.',
            mb_email: '이메일 확인 결과가 올바르지 않습니다.',
            mb_password: { required:'비밀번호를 입력하세요.', minlength:'최소 3자 이상 입력하세요.' },
            mb_password_re: { required:'비밀번호 확인을 입력하세요.', equalTo:'비밀번호가 일치하지 않습니다.' },
            mb_password_q: '비밀번호 분실시 질문을 선택하거나 입력하세요.',
            mb_password_a: '비밀번호 분실시 답변을 입력하세요.',
            mb_name: { required:'이름을 입력하세요.', minlength:'최소 2자 이상 입력하세요.' },
            mb_birth: '올바른 형식이 아닙니다.',
            //mb_profile: "만 14세가 지나지 않은 어린이는 정보통신망 이용촉진 및 정보보호 등에 관한 법률 제 31조 1항의 규정에 의하여 법정대리인의 동의를 얻어야 하므로 법정대리인의 이름과 연락처를 '자기소개'란에 별도로 입력하시기 바랍니다.",
            wr_key: '자동등록방지용 코드가 맞지 않습니다.'
        }
    });
});
//]]>
</script>