function Option_ch(choice,Types) {
	var hoya = document.input;
	if(Types=='notice') {
		if(hoya.notice.value==1) hoya.notice.value = "";
		else hoya.notice.value = 1;
		document.getElementById("imgnotice").src = YHBSkin+'/option_notice'+hoya.notice.value+'.gif';
		}

	if(Types=='remail') {
		if(hoya.reply_mail.value==1) hoya.reply_mail.value = "";
		else hoya.reply_mail.value = 1;
		document.getElementById("imgremail").src = YHBSkin+'/option_remail'+hoya.reply_mail.value+'.gif';
		}

	if(Types=='secret') {
		if(hoya.secret.value==1) hoya.secret.value = "";
		else hoya.secret.value = 1;
		document.getElementById("imgsecret").src = YHBSkin+'/option_secret'+hoya.secret.value+'.gif';
		}
	}

function write_check() {
	var hoya = document.input;
	if(hoya.html[2].checked==true) {
		var EditorDoc = document.getElementById("htmlPage");
		var tempEditorDoc = EditorDoc.contentWindow.document;
	}
	if(!hoya.title.value){
		alert('제목을 적어주십시요~~!');
		hoya.title.focus();
		return false;
		}
	if(!YHBWriteName) {
		if(!hoya.name.value){
			alert('작성자 이름을 적어주십시오~~!');
			hoya.name.focus();
			return false;
			}
		}
	if(hoya.html[2].checked==true) {
		if(!tempEditorDoc.body.innerHTML) {
			alert('내용을 적어주십시요~~!');
			return false;
		}
	} else {
		if(!hoya.content.value) {
			alert('내용을 적어주십시요~~!');
			hoya.content.focus();
			return false;
		}
	}
	if(!YHBWritePass) {
		if(!hoya.pass.value){
			alert('비밀번호를 적어주십시요~~!');
			hoya.pass.focus();
			return false;
			}
		if(hoya.pass.value.length < 4){
			alert('비밀번호는 4자이상 적어주십시오~~!');
			hoya.pass.focus();
			return false;
			}
		}
	var num = hoya.length;
	var upfilecheck = "";
	var filenames = "";
	for(i=0;i<num;i++) {
		if(hoya[i].type=='file') {
			if(hoya[i].value) {
				upfilecheck = 1;
				}
			else {
				if(!filenames) filenames = hoya[i];
				}
			}
		}
	
	if(YHBusePds) {
		if(!upfilecheck) {
			alert('자료실 전용으로 쓰이고 있습니다.'+'\n'+'업로드할 파일을 찾아주십시오');
			filenames.focus();
			return false;
		}
	}
	if(hoya.html[2].checked==true && tempEditorDoc.body.innerHTML) {
		hoya.content.value = tempEditorDoc.body.innerHTML;
	}
}
window.onload = start_focus;
function start_focus() {
	document.input.title.focus();
	}
