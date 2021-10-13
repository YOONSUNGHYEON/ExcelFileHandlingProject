window.onload = function() {
	findCategoryList();
}

function findCategoryList() {	
	$.ajax({
		type: 'GET',
		url: "mapper.php?method=findCategoryList",
		dataType: "json",
		success: function(categoryList) {		
			let option = "";
			for(let i = 0; i<categoryList.length; i++) {				
				option += '<option id="' + categoryList[i]['nCategorySeq']+'"';
				option += ' name="'+ categoryList[i]['nCategorySeq']+'"';
				option += ' value = "' + categoryList[i]['nCategorySeq']+'">';
				option += categoryList[i]['sName'] + '</option>';
			}
	
			$("#categorySelect").html(option);
		}
	});
}
//http://prod-webdev.danawa.com/info/?pcode=XXXX
$(function(){ 
	$("#search-btn").click(function(){
		let categorySeq = $("select[name=categorySelect]").val();
		 $.ajax({
            url: "mapper.php?method=findListByCategorySeq",
            type: "POST",
			dataType: "json",
            data: {
				categorySeq : categorySeq
			}, 
            success: function(standardProductList){
				let standardProductTable = "";				
				for(let i = 0; i<20; i++) {	
					console.log(standardProductList[i]);
					standardProductTable += '<tr style="cursor:pointer;">';
					standardProductTable +='<td><a target="_blank" href="http://prod-webdev.danawa.com/info/?pcode='+standardProductList[i]["nStandardProductSeq"]+
											'">' + standardProductList[i]["nStandardProductSeq"] + '</a></td>';
					standardProductTable +='<td>' + standardProductList[i]['sCategoryName'] + '</td>';
					standardProductTable +='<td>' + standardProductList[i]['sName'] + '</td>';
					standardProductTable +='<td>' + standardProductList[i]['nLowestPrice'] + '</td>';
					standardProductTable +='<td>' + standardProductList[i]['nMobileLowestPrice'] + '</td>';
					standardProductTable +='<td>' + standardProductList[i]['nAveragePrice'] + '</td>';
					standardProductTable +='<td>' + standardProductList[i]['nCooperationCompayCount'] + '</td>';			
					standardProductTable += '</tr>';
			}
	
			$("#standardProductTable").html(standardProductTable);
            },
           
        }) 
		
	})
})