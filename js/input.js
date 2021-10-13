$("#file").on('change',function(){
  var fileName = $("#file").val();
  $(".upload-name").val(fileName);
});

$(function(){ 
	$("#btn").click(function(){
		var formData = new FormData();
		formData.append("productOption", $("select[name=productOption]").val()); 
		formData.append("file", $("input[name=file]")[0].files[0]);
		console.log("클릭");
		$.ajax({
			url: "mapper.php?method=uploadFile",
            type: "POST",
			data: formData,
  			cache: false,
   			processData: false, 
   			contentType: false, 
            success: function(result){
				console.log(result);
					if(result==400) {
						alert("엑셀 파일을 업로드 해주세요");	
					}else if(result==401) {
						alert("엑셀 파일 형식만 업로드 해주세요");	
					} else {
						alert("업로드를 성공했습니다. 하지만 "+result+"해당 상품 코드 데이터가 유효하지 않습니다.");
					}													
            },
			error:function(request,status,error){
        		alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
       		}

    	}) 
	})
})

