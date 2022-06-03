$(document).ready(function() {
	//画像読込み初期値
	$("#uploadImage").css("height","500px");
	//初期ケースをセット
	var casesample = $("#casesample").val();
	//console.log(casesample);
	$("#case").attr("src","./files/"+casesample+".png");
	$(".price").html(waku[casesample]);
	$("#price").val(waku[casesample]);
	//console.log(waku[casesample]);
	
	$("#casesample").change(function(){
		var casesample = $("#casesample").val();
		$("#case").attr("src","./files/"+casesample+".png");
		$(".price").html(waku[casesample]);
		$("#price").val(waku[casesample]);
	});
	$("#value").change(function(){
		var casesample = $("#casesample").val();
		var val = parseInt($(this).val());
		var price = parseInt(waku[casesample]);
		var totals = val * price;
		$(".price").html(separate(totals));
		//$("#price").val(totals);
	});
	
	//$("#img_bg").draggable();
	$("#re").draggable();
	
	//デフォルト
	$("#itemView").css("z-index", "5");
	$("#img_bg").css("z-index", "1");
	$('#simulate-wrap')
		// マウスポインターが画像に乗った時の動作
		.mouseover(function(e) {
			$("#itemView").css("z-index", "1");
			$("#img_bg").css("z-index", "5");
			$("#img_bg").css("opacity", "0.8");
		})
		// マウスポインターが画像から外れた時の動作
		.mouseout(function(e) {
			$("#itemView").css("z-index", "5");
			$("#img_bg").css("z-index", "1");
			$("#img_bg").css("opacity", "1");
	});
	
	
		function refreshSwatch() {
			var slider = $("#slider").slider("value");
			//console.log(slider);
			var val = parseInt(slider) - 50;
			if (w === undefined) {
				if ($("#wresult").val() == 0) {
					var b = $('#uploadImage');
					var c = b.width();
					$("#wresult").val(c);
					var w = c + (val * 10);
					//var d = b.height();
					//$("#hresult").val(c);
					//var h = d + (val * 10);
				} else {
					var w = parseInt($("#wresult").val()) + (val * 10);
					//var h = parseInt($("#hresult").val()) + (val * 10);
				}
			}
			if (w) {
				$("#uploadImage").css("width", w);
				//$("#img_bg").css("width", w);
				$("#re").css("width", w);
				$("#uploadImage").css("height", "auto");
				//$("#img_bg").css("height", "auto");
				$("#now_w").val(w);
				$("#re").css("height", "450px");
			}
		}
		$(function() {
			$("#slider").slider({
				orientation: "horizontal",
				range: "min",
				max: 100,
				value: 50,
				slide: refreshSwatch,
				change: refreshSwatch
			});
			$("#slider").slider("value", 50);
		});
	
		$('.alphaImage').alphaimage({
			colour: "#ffffff"
		});
		$('.downloadButton a').on('click', function(e) {

			var hrefPath = $(this).attr('href');
			var fileName = $(this).attr('href').replace(/\\/g, '/').replace(/.*\//, '');

			$target = $(e.target);
			$target.attr({
				download: fileName,
				href: hrefPath
			});

		});
	
		$("#shot").click(function(){
			$(".errors").html("");
			var value = $("#value").val();
			var product_name = $("#product_name").val();
			if(product_name == ""){
				$("#errors1").html("商品名を入力してください。");
				Tops();
				return false;
			}
			if(value == ""){
				$("#errors2").html("数量を入力してください。");
				Tops();
				return false;
			}
			
			$("#modal-overlay").css("display", "block");
			centeringModalSyncer();
			screenshot('#simulate');
			//screenshot('#shots');
		});

	
	
});
function screenshot(selector) {
			$("#loader").css("display", "inline");
			$(".downloadButton").css("display", "none");
			$("#simulate").css("width", "300px");
			$("#simulate").css("height", "450px");
			var getCanvas;
			var element = $(selector)[0];
			html2canvas(element, {
				onrendered: function(canvas) {
					var imgData = canvas.toDataURL();
					//$("#sample").attr("src",imgData);
					getCanvas = canvas;
					//var name = $("#wakuimgs").val();
					//var name2 = $("#wakuimgs2").val();
					var chk = $("[name=chkon]:checked").val();
					var cnf = $("#cnf").val();
					//現在の画像サイズ及び元ファイル情報及び左位置
					//var size = $("#re").css("width");
					var size = $("#uploadImage").css("width");
					var left = $("#re").css("left");
					var top = $("#re").css("top");
					var files = $("#files").val();
					$.post("./convert.php?mode=img", {
						img: imgData,chk:chk,cnf:cnf,size:size,files:files,left:left,top:top
					}).done(function(res) {
						//console.log(res);
						screenshot2(selector);
						//$('#download2').attr('href', "./convert/"+name+".png");
						//$('#download3').attr('href', "./convert/"+name2+".png");
					});
					$('#screen_image')[0].src = imgData;
					$('.alphaImage').alphaimage({
						colour: "#ffffff"
					});
				}
			});
		}

		function screenshot2(selector) {
			for (var i = 0; i <= 500; i++) {
				//console.log(i);
			}
			$("#itemView").css("display", "none");
			var getCanvas;
			var element = $(selector)[0];
			html2canvas(element, {
				onrendered: function(canvas) {
					var imgData2 = canvas.toDataURL();
					getCanvas = canvas;
					//var name = $("#backimg").val();
					var chk = $("[name=chkon]:checked").val();
					var cnf = $("#cnf").val();
					var value = $("#value").val();
					var product_name = $("#product_name").val();
					var price = $("#price").val();
					var status = $("[name=status]:checked").val();
					var casesample = $("#casesample").val();
					$.post("./convert.php?mode=img2", {
						img2: imgData2,chk:chk,cnf:cnf,product_name:product_name,value:value,price:price,status:status,casesample:casesample
					}).done(function(res) {
						var ret = res.split("■");
						$(".downloadButton").css("display", "block");
						$(".downloadButton").css("top", "590px");
						$(".downloadButton").css("position", "absolute");
						$(".downloadButton").css("z-index", "10");
						$("#download0").css("display", "block");
						$("#itemView").css("display", "block");
						$("#jan").css("display", "block");
						$("#loader").css("display", "none");
						$('#download0').attr('href', "./convert/"+ret[1]);
						$('#download0').attr('download', ret[1]);
						
						$("#shot").css("display","none");
						$("#result").css("display","block");
						$("#result").html(ret[0]);
						$("#modal-overlay").css("display", "none");
						//自動的に遷移
						$(".buy_button").click();
						console.log(res);
					});
					$('#screen_image2')[0].src = imgData2;
					$('.alphaImage').alphaimage({
						colour: "#ffffff"
					});
				}
			});

		}
		function screenshot3() {
			var name = $("#wakus").val();
			//JANコード発行
			$.post("./convert.php?mode=jan", {
				name:name
			}).done(function(res) {
				$("#janimg").html("<img src=\"./img/"+res+"_jancode.png\">");
				$("#jan").val(res);
			});
		}


		function erase_screenshot() {
				$('#screen_image')[0].src = "";
				$('#screen_image2')[0].src = "";
				$('#download')[0].href = "#";
				$('#download')[0].innerHTML = "";
			}
		function separate(num){//カンマ区切り
		    return String(num).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
		}
     function centeringModalSyncer() {
 
		 //画面(ウィンドウ)の幅、高さを取得
		 var w = $( window ).width() ;
		 var h = $( window ).height() ;
		 
		 // コンテンツ(#modal-content)の幅、高さを取得
		 // jQueryのバージョンによっては、引数[{margin:true}]を指定した時、不具合を起こします。
		 var cw = $( "#modal-content" ).outerWidth( {margin:true} );
		 var ch = $( "#modal-content" ).outerHeight( {margin:true} );
		 var cw = $( "#modal-content" ).outerWidth();
		 var ch = $( "#modal-content" ).outerHeight();
		 
		 //センタリングを実行する
		 $( "#modal-content" ).css( {"left": ((w - cw)/2) + "px","top": ((h - ch)/2) + "px"} ) ;
		 
	}
	function Tops(){
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
	}