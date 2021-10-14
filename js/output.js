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
			for (let i = 0; i < categoryList.length; i++) {
				option += '<option id="' + categoryList[i]['nCategorySeq'] + '"';
				option += ' name="' + categoryList[i]['nCategorySeq'] + '"';
				option += 'value = "' + categoryList[i]['nCategorySeq'] +  '">';
				option += categoryList[i]['sName'] + '</option>';
			}

			$("#categorySelect").html(option);
		}
	});
}
function findStandardProductList(page) {
	$.ajax({
		url: "mapper.php?method=findStandardProductList",
		type: "POST",
		dataType: "json",
		data: {
			page: page
		},
		success: function(standardProductList) {
			console.log(standardProductList);
			/*let standardProductTable = "";
			for (let i = 0; i < 20; i++) {
				standardProductTable += '<tr style="cursor:pointer;">';
				standardProductTable += '<td><a target="_blank" href="http://prod-webdev.danawa.com/info/?pcode=' + standardProductList[i]["nStandardProductSeq"] +
					'">' + standardProductList[i]["nStandardProductSeq"] + '</a></td>';
				standardProductTable += '<td>' + standardProductList[i]['sCategoryName'] + '</td>';
				standardProductTable += '<td>' + standardProductList[i]['sName'] + '</td>';
				standardProductTable += '<td>' + standardProductList[i]['nLowestPrice'] + '</td>';
				standardProductTable += '<td>' + standardProductList[i]['nMobileLowestPrice'] + '</td>';
				standardProductTable += '<td>' + standardProductList[i]['nAveragePrice'] + '</td>';
				standardProductTable += '<td>' + standardProductList[i]['nCooperationCompayCount'] + '</td>';
				standardProductTable += '</tr>';
			}
			$("#standardProductTable").html(standardProductTable);

			let pagination = "";
			pagination += '<a class="arrow" href="#"><<</a>';
			pagination += '<a class="arrow" href="#">&lt;</a>';
			pagination += '<a href="#">1</a>';
			pagination += '<a class="active" href="#">2</a>';
			pagination += '<a href="#">3</a>';
			pagination += '<a href="#">4</a>';
			pagination += '<a href="#">5</a>';
			pagination += '<a class="arrow" href="#">&gt;</a>';
			pagination += '<a class="arrow" href="#">>>;</a>';
			$("#standardPagination").html(pagination);*/

		},
	})
}

//http://prod-webdev.danawa.com/info/?pcode=XXXX
$(function() {
	$("#search-btn").click(function() {
		let categorySeq = $("select[name=categorySelect]").val();
		$.ajax({
			url: "mapper.php?method=findListByCategorySeq",
			type: "POST",
			dataType: "json",
			data: {
				categorySeq: categorySeq
			},
			success: function(standardProductList) {
				let standardProductTable = "";
				for (let i = 0; i < 20; i++) {
					standardProductTable += '<tr style="cursor:pointer;">';
					standardProductTable += '<td><a target="_blank" href="http://prod-webdev.danawa.com/info/?pcode=' + standardProductList[i]["nStandardProductSeq"] +
						'">' + standardProductList[i]["nStandardProductSeq"] + '</a></td>';
					standardProductTable += '<td>' + standardProductList[i]['sCategoryName'] + '</td>';
					standardProductTable += '<td>' + standardProductList[i]['sName'] + '</td>';
					standardProductTable += '<td>' + standardProductList[i]['nLowestPrice'] + '</td>';
					standardProductTable += '<td>' + standardProductList[i]['nMobileLowestPrice'] + '</td>';
					standardProductTable += '<td>' + standardProductList[i]['nAveragePrice'] + '</td>';
					standardProductTable += '<td>' + standardProductList[i]['nCooperationCompayCount'] + '</td>';
					standardProductTable += '</tr>';
				}

				$("#standardProductTable").html(standardProductTable);
			},

		})

	})
})