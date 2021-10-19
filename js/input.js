window.onload = function() {
	$("#progressLoading").hide();
}

$("#file").on('change', function() {
	var fileName = $("#file").val();
	$(".upload-name").val(fileName);
});

/* 파일 업로드 부분 */
$(function() {
	$("#btn").click(function() {
		
		/* 파일 데이터 저장 */
		let formData = new FormData();
		formData.append("productOption", $("select[name=productOption]").val());
		formData.append("file", $("input[name=file]")[0].files[0]);
		
		/* productOption 기준 상품(2) & 협력사 상품(3) */
		let productOption = $("select[name=productOption]").val()
		let ext = $('#file').val().split('.').pop().toLowerCase();
		
		/* 파일 업로드 유효서 검사 */
		if (!$("input[name=file]")[0].value) {
			alert("엑셀 파일을 업로드 해주세요");
		} else {
			if ($.inArray(ext, ['xlsx', 'xls']) == -1) {
				alert('엑셀 파일 형식만 업로드 해주세요');
			} else {
				$.ajax({
					url: "mapper.php?method=uploadFile",
					type: "POST",
					data: formData,
					cache: false,
					processData: false,
					contentType: false,
					dataType: "json",
					beforeSend: function() {
						$("#progressLoading").show();
					},
					complete: function() {
						$("#progressLoading").hide();
					},
					error: function() {
						alert("통신이 실패했습니다.");
					},
					success: function(uploadResponse) {	
						console.log(uploadResponse);					
						if (uploadResponse["code"] == 400) {
							alert("엑셀 파일을 업로드 해주세요");
						} else if (uploadResponse["code"] == 401) {
							alert("엑셀 파일 형식만 업로드 해주세요");
						} else if (uploadResponse["code"] == 402) {
							if(productOption==2) {
								alert("기준 상품 엑셀 파일이 아닙니다.");
							} else {
								alert("협력사 상품 엑셀 파일이 아닙니다.");
							}							
						} else {
							let msg = uploadResponse["successRowCount"] + "개의 데이터를 업로드 성공했습니다. \n업로드 실패한 데이터는 " + uploadResponse['errorRowCount'] + "개 입니다. \n업로드 실패 데이터 seq 목록\n";
							for(let i=0; i<uploadResponse['errorRow'].length; i++) {
								msg += uploadResponse['errorRow'][i] + "\n";
							}
							alert(msg);
						}
					}

				})
			}
		}

	})
})

