window.onload = function() {
	findCategoryList();
}
function download() {
	let productArray = [];
	let productDataObj = {};
	for(let i=1; i<=$('#productTable tbody tr').length; i++){
		let tempQuery = "#standardProductTbody > tr:nth-child("+i+")>";
		productDataObj.nStandardProductSeq =$(tempQuery+"td:nth-child(1)").text();
		productDataObj.sCategoryName =$(tempQuery+"td:nth-child(2)").text();  
		productDataObj.sName =$(tempQuery+"td:nth-child(3)").text();
		productDataObj.nLowestPrice =$(tempQuery+"td:nth-child(4)").text();
		productDataObj.nMobileLowestPrice =$(tempQuery+"td:nth-child(5)").text();
		productDataObj.nAveragePrice =$(tempQuery+"td:nth-child(6)").text();
		productDataObj.nCooperationCompayCount =$(tempQuery+"td:nth-child(7)").text();
		productArray.push(productDataObj);
		productDataObj={};
	}
	let jsonData = JSON.stringify(productArray);
	//console.log(jsonData);
	$.ajax({
		url: "mapper.php?method=download",
		type: "POST",
		dataType: "json",
		data: {
			productArrObj : jsonData
		},
		success: function(response) {
			console.log(response);
			
		}
	})
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
				option += 'value = "' + categoryList[i]['nCategorySeq'] + '">';
				option += categoryList[i]['sName'] + '</option>';
			}

			$("#categorySelect").html(option);
		}
	});
}
function findListByCategorySeq(){
	findCooperationProductList(1,1,1);
	findStandardProductList(1,1,1);
}
function findCooperationProductList(page, option, order) {
	let categorySeq = $("select[name=categorySelect]").val();
	$.ajax({
		url: "mapper.php?method=findCooperationProductListByCategorySeq",
		type: "POST",
		dataType: "json",
		data: {
			categorySeq: categorySeq,
			page: page,
			option: option,
			order: order, //오름차순 = 1, 내림차순=2;
		},
		success: function(tableData) {
			console.log(tableData);
			}
			
	})
}

function findStandardProductList(page, option, order) {
	let categorySeq = $("select[name=categorySelect]").val();
	$.ajax({
		url: "mapper.php?method=findStandardProductListByCategorySeq",
		type: "POST",
		dataType: "json",
		data: {
			categorySeq: categorySeq,
			page: page,
			option: option,
			order: order, //오름차순 = 1, 내림차순=2;
		},
		success: function(tableData) {
			console.log(tableData);
			let standardProductThead = "";
			for (let i = 1; i <= 7; i++) {
				if (i == 2) {
					standardProductThead += '<th>카테고리</th>';
				} else if (i == 6) {
					standardProductThead += '<th>평균가</th>';
				} else if (option == i) {
					if (order == 1) {
						standardProductThead += '<th><a id="option' + i + '" class="activeThead" onClick="findStandardProductList(1,' + i + ',2)" href="#a"></a></th>';
					} else if (order == 2) {
						standardProductThead += '<th><a id="option' + i + '" class="activeThead" onClick="findStandardProductList(1,' + i + ',1)" href="#a"></a></th>';
					}
				} else {
					standardProductThead += '<th><a id="option' + i + '" onClick="findStandardProductList(1,' + i + ',1)" href="#a"></a></th>';
				}
			}
			$("#standardProductThead").html(standardProductThead);
			
			$('#option1').html('상품 코드');
			$('#option3').html('상품 명');
			$('#option4').html('최저가');
			$('#option5').html('모바일 최저가');
			$('#option7').html('업체 수');

			if (order == 1) {
				$('.activeThead').append('↓');
			} else {
				$('.activeThead').append('↑');
			}

			let standardProductTable = "";
			for (let i = 0; i < tableData['nCurrentCount'] - 1; i++) {
				standardProductTable += '<tr style="cursor:pointer;">';
				standardProductTable += '<td><a target="_blank" href="http://prod-webdev.danawa.com/info/?pcode=' + tableData[i]["nStandardProductSeq"] +
					'">' + tableData[i]["nStandardProductSeq"] + '</a></td>';
				standardProductTable += '<td>' + tableData[i]['sCategoryName'] + '</td>';
				standardProductTable += '<td>' + tableData[i]['sName'] + '</td>';
				standardProductTable += '<td>' + tableData[i]['nLowestPrice'] + '</td>';
				standardProductTable += '<td>' + tableData[i]['nMobileLowestPrice'] + '</td>';
				standardProductTable += '<td>' + tableData[i]['nAveragePrice'] + '</td>';
				standardProductTable += '<td>' + tableData[i]['nCooperationCompayCount'] + '</td>';
				standardProductTable += '</tr>';
			}
			$("#standardProductTbody").html(standardProductTable);

			let pagination = "";
			let preArrow = Number(tableData['aPageData']['nCurrentPage']) - Number(tableData['aPageData']['nBlockPage']);
			let nextArrow = Number(tableData['aPageData']['nCurrentPage']) + Number(tableData['aPageData']['nBlockPage']);
			pagination += '<a class="arrow" onClick="findStandardProductList(1,' + option + ','+ order +')" href="javascript:void(0);"><<</a>';
			pagination += '<a class="arrow" onClick="findStandardProductList('+ preArrow +',' + option + ','+ order +')" href="javascript:void(0);">&lt;</a>';
			for (let i = tableData['aPageData']['nStartPage']; i <= tableData['aPageData']['nEndPage']; i++) {
				if (tableData['aPageData']['nCurrentPage'] == i) {
					pagination += '<a class="active" onClick="findStandardProductList(' + i + ',' + option + ','+ order +')"  href="javascript:void(0);">' + i + '</a>';
				} else {
					pagination += '<a onClick="findStandardProductList(' + i + ',' + option + ','+ order +')"  href="javascript:void(0);">' + i + '</a>';
				}
			}
			pagination += '<a class="arrow" onClick="findStandardProductList('+ nextArrow +',' + option + ','+ order +')" href="javascript:void(0);">&gt;</a>';
			pagination += '<a class="arrow" onClick="findStandardProductList(' + tableData['aPageData']['nTotalPage'] + ',' + option + ','+ order +')" href="javascript:void(0);" )">>></a>';
			$("#standardPagination").html(pagination);

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