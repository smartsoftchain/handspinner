<?php
header("Content-Type: text/html; charset=utf-8");
header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

exec("/bin/find ./upload/ -type f -mtime +1 -exec rm -fv {} \;");
exec("/bin/find ./upload/ -type f -mtime +1 -exec rm -fv {} \;");

$path = "./inc";
	require_once($path."/conf.php");


$files = "";
if($_FILES['file']['tmp_name']){
	$fileinfo = pathinfo($_FILES["file"]["name"]);
	$fileext = strtoupper($fileinfo["extension"]);
	$files = date("YmdHis").".".strtolower($fileext);
	move_uploaded_file($_FILES['file']['tmp_name'], './upload/'.$files);
	$files = $files;
}elseif($_REQUEST["img"]){
	$files = $_REQUEST["img"];
}

//テスト用
//$files = "20170210092902.jpg";

if('./upload/'.$files){
	list($imgwidth, $imgheight) = getimagesize('./upload/'.$files);
	$upwsize = 625;
	$uphsize = round($imgheight/($imgwidth/625));
}

//数量リストボックス
$value = array();
for($i=1;$i<=99;$i++){
	$value[] = array("key"=>$i);
}

//初期値
$color1 = "<option value=\"\">選択してください。</option>";
foreach($case_list as $key => $val){
	if($key == "black"){$sel="selected";}else{$sel="";}
	$color1 .= "<option value=\"".$key."\" ".$sel.">".$color_list[$key]."</option>";
}
$price1 = $case_list["black"][0][1];
$price2 = number_format($case_list["black"][0][1]);


$ua = $_SERVER['HTTP_USER_AGENT'];

//フォント一覧
$font_list = "";
if ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false) || (strpos($ua, 'iPhone') !== false) || (strpos($ua, 'Windows Phone') !== false)) {
	foreach($ar_sp as $val){
		$font_list .= "<option value=\"".$val."\">".$val."</option>";
	}
} else {
	foreach($ar as $val){
		$font_list .= "<option value=\"".$val."\">".$val."</option>";
	}
}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>オリジナルハンドスピナー作成画面</title>
<meta content="authenticity_token" name="csrf-param">
<meta content="width=device-width, initial-scale=1" name="viewport">
<link href="css/basic_case.css" media="all" rel="stylesheet">
<link rel="stylesheet" href="css/excolor.css" type="text/css" />
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<script data-turbolinks-track="true" src="js/tn-menu.js"></script>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

<!--script src="js/fabric.min.js"></script-->
<script src="js/fabric.js"></script>
<!--script src="js/jquery.alphaimage.js"></script-->

<script>
var $j = jQuery.noConflict();
$j(function(){
	$j('#stroke-color').excolor();
	$j('#fill-color').excolor();
});
</script>
	
</head>


<body>

<header class="contents" id="header">
<div class="navbar navbar-fixed-top" role="navigation">
	<div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sp">Toggle navigation</span>
          <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="index.html"><img src="images/logo.png" /></a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
<!--
          <li><a class="hatena" href="#" target="_blank"><i class="fa fa-question-circle"></i> よくある質問</a></li>
          <li><a class="about" href="#" target="_blank"><i class="fa fa-file-text-o"></i> 利用ガイド</a></li>
-->
        </ul>
 	  </div>
    </div>
</div>
</header>
  
<section id="contents_simulate">
  <div class="container">
    <span class="cle"><i class="fa fa-picture-o"></i></span>
    <h1>オリジナルハンドスピナー作成画面</h1>
    

    <!--form action="#" id="orderForm" enctype="" method="post" accept-charset="utf-8"-->
      <div class="row devise" id="phone_case">
        <div class="col-md-7">
          <div id="simulate-box">
            <div id="simulate-wrap">
              <!-- 機種ごとに合成部分の処理をわける -->
              
              <!-- 画像の合成部分の処理-->
              	
              <canvas id="myCanvas" width="625" height="625"></canvas>
              <div id="slider"></div>

            </div><!-- /simulate-wrap -->
          </div><!-- /simulate-box -->
        </div><!-- /col-->

		<div id="views">
			<ul>
				<li id="views1">確 定</li>
				<!--li id="str1">文字入力</li-->
			</ul>
		</div>
        <div class="col-md-4 detail" id="details">
              <!-- 画像の合成部分の処理-->
<br><br><br>
			<div class="row">
              <h2><i class="fa fa-cog"></i>ハンドスピナーの色を選択</h2>
              	  
                <div class="row">
	          	
	                <div class="col-md-12">
		                <select class="form-control order-key" id="casecolr" name="casecolr">
			                	<option value="">選択してください。</option>
							
		                </select>
		             </div>
		         </div>
		     </div>
          	<div class="row">
               <h2><i class="fa fa-cog"></i>注文数量を選択</h2>
                <div class="row">
                <div class="col-md-12">
                	<select class="form-control order-key" name="value" id="value">
                	<?php
                		for($i=1;$i<=99;$i++){
                			echo "<option value=\"".$i."\">".$i."</option>";
                }
                	?>
                	</select>
                    <div id="errors2" class="errors" style="color:red;"></div>
                </div>
                
                <div class="col-md-12">
                    <input type="hidden" name="status" id="status" value="1">
                </div>
              </div>
                	
              <p class="commodity_price">
                ￥<strong class="price"></strong>
                    <input id="price" name="price" type="hidden" value="" />
                <span>(税込)</span>
              </p>
              <div class="order_option">
					<p><input type="hidden" name="chkon" id="chkon" value="on"></p>
                <button class="btn btn-lg btn-primary btn-wide btn-convert" id="shot" onclick="return false;">注文する</button>
					<input type="hidden" name="cnf" id="cnf" value="<?php echo date('YmdHis'); ?>" >
              </div>
              ※お届けするハンドスピナーのデザインは、商品の入荷時期によって多少事なる場合があります。予めご了承下さい。<br>
<br>
※素材：プラスティック製<br>
※発送まで３営業日ほど頂きます。<br>
※お支払い方法：クレジットカード・銀行振込<br>
<br>
        </div><!-- / col-->
    </div>    

    <!--/form-->
	<div id="result" style="display:none;"></div>
	    <p class="attention">
	    </p>

	    <form accept-charset="UTF-8" action="" data-no-turbolink="true" id="convert_form" method="post"><div style="margin:0;padding:0;display:inline"><input name="authenticity_token" type="hidden" value="/OkmKJNNIkWqyVq7NgETUX0bTxWqpUPdXwWYBR7q1xQ=" /></div>

			<input type="hidden" name="wresult" id="wresult" value="0">
			<input type="hidden" name="now_w" id="now_w" value="0">
			<input type="hidden" id="hresult" name="hresult" value="0">
			<input type="hidden" id="files" name="hresult" value="<?php echo $files;?>">
			<input type="hidden" id="x" name="x" value="150">
			<input type="hidden" id="y" name="y" value="150">
			<input type="hidden" id="h" name="h" value="<?php echo $upwsize; ?>">
			<input type="hidden" id="w" name="w" value="<?php echo $uphsize; ?>">
			<input type="hidden" id="xt" name="xt" value="150">
			<input type="hidden" id="yt" name="yt" value="150">
			<input type="hidden" id="slide" name="slide" value="50">
			<input type="hidden" id="rote" name="rote" value="">
			<input type="hidden" id="reedit" name="reedit" value="">
			<input type="hidden" id="reedit2" name="reedit2" value="">
			<input type="hidden" id="reedit3" name="reedit3" value="">
			<input type="hidden" id="img2" name="img2" value="">
	    </form>
	</div>    
	</section>
	<div id="modal-overlay">
		<div id="modal-content"><img src="./images/loadingcircle.gif"></div>
	</div>
			
			
<footer id="footer">
<div class="container">

    <nav>
    </nav>
    <div id="copyright">&#169;
        <span itemprop="name">マイスピナーつくーる</span>
        <span itemprop="copyrightYear"></span>
    </div>
    
  </div>
</footer>


<div class="item" style="display:none;">
    <div class="item_loading"></div>
</div>
<script type="text/javascript">
    var $j = jQuery.noConflict();
    $j(function(){
        $j("#casecolr").change(function(){
        	var color = $j(this).val();
			$j.post(
				"scp.php?mode=color",
				{ color: color })
				.done(function(res) {
					$j("#casetype").html(res);
					var h = $j("#h").val();
			});
        });
        $j("#value").change(function(){
        	var color = $j("#casecolr").val();
        	var value = $j("#value").val();
			$j.post(
				"scp.php?mode=price",
				{ color: color,value:value })
				.done(function(res) {
					console.log(res);
					$j(".price").html(res);
					$j("#price").val(res);
			});
        });
        
		$j("#shot").click(function(){
			$j(".errors").html("");
			var value = $j("#value").val();
			var product_name = $j("#product_name").val();
			if(value == ""){
				$j("#errors2").html("数量を入力してください。");
				Tops();
				return false;
			}
			
			$j("#modal-overlay").css("display", "block");
			centeringModalSyncer();
			
			
			    //$j("#reedit").trigger("click");
			    $j("#reedit2").trigger("click");

			var canvas = document.getElementById("myCanvas");  //canvas要素を取得
			if (canvas.getContext) {
			    var  ctx = canvas.getContext('2d');
			    var img=new Image();
			    var type = 'image/png';  
				img.src = canvas.toDataURL(type);
			    
			    //console.log(img);
			    console.log("start2");
			    img.onload = function(e){
			    	
					
			    	//console.log(img.src);
			    	 //$j("#reedit2").trigger("click");
					/*
			    	 var img2=new Image();
			    	var type = 'image/png';  
			    	img2.src = canvas.toDataURL(type);
			    	$j("#img2").val(img2.src);
					*/
					var cnf = $j("#cnf").val();
					var value = $j("#value").val();
					var price = $j("#price").val();
					var x = $j("#x").val();
					var y = $j("#y").val();
					var xt = $j("#xt").val();
					var yt = $j("#yt").val();
					var h = $j("#h").val();
					var w = $j("#w").val();
					//var img2 = $j("#img2").val();
					var rote = $j("#rote").val();
					var files = $j("#files").val();
console.log("start3");
					$j.post("./convert.php?mode=img", {
						img: img.src,cnf:cnf,value:value,price:price,x:x,y:y,w:w,h:h,files:files,xt:xt,yt:yt,rote:rote
					}).done(function(res) {
						
			    	 	$j("#reedit3").trigger("click");

						var ret = res.split("■");
						$j(".downloadButton").css("display", "block");
						$j(".downloadButton").css("top", "590px");
						$j(".downloadButton").css("position", "absolute");
						$j(".downloadButton").css("z-index", "10");
						$j("#download0").css("display", "block");
						$j("#itemView").css("display", "block");
						$j("#jan").css("display", "block");
						$j("#loader").css("display", "none");
						$j('#download0').attr('href', "./convert/"+ret[1]);
						$j('#download0').attr('download', ret[1]);
						
						$j("#shot").css("display","none");
						$j("#result").css("display","block");
						$j("#result").html(ret[0]);
						$j("#modal-overlay").css("display", "none");
						//自動的に遷移
						$j(".buy_button").click();

						
					});

			    };
			}

		});
        
    });
    
     function centeringModalSyncer() {
 
		 var w = $( window ).width() ;
		 var h = $( window ).height() ;
		 var cw = $( "#modal-content" ).outerWidth( {margin:true} );
		 var ch = $( "#modal-content" ).outerHeight( {margin:true} );
		 var cw = $( "#modal-content" ).outerWidth();
		 var ch = $( "#modal-content" ).outerHeight();
		 
		 //センタリングを実行する
		 $( "#modal-content" ).css( {"left": ((w - cw)/2) + "px","top": ((h - ch)/2) + "px"} ) ;
		 
	}
    
    
    //$(window).load(function(){
		$("#casecolr").html('<?php echo $color1; ?>');
		$(".price").html('<?php echo $price2; ?>');
		$("#price").val('<?php echo $price1; ?>');
	//});
	
	
	


$(function(){
  var maxWidth = 625
  var f1 = $("#casecolr").val();
  var f2 = "<?php echo $files; ?>";
  var path = "case/";
  
  var $x1 = parseInt($('#x').val());
  var $y1 = parseInt($('#y').val());
  var $h1 = parseInt($('#h').val());
  var $w1 = parseInt($('#w').val());
  var $xt = parseInt($('#xt').val());
  var $yt = parseInt($('#yt').val());
  
	var canvas = new fabric.Canvas('myCanvas',
	    {
	        selection : false,
	        //controlsAboveOverlay:true,
	        //centeredScaling:true,
	        //allowTouchScrolling: true
	    }
	);


	canvas.setBackgroundImage(path+f1+"1.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625
        });

	canvas.setOverlayImage(path+f1+"2.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625,
            selectable:false
        });


	fabric.Image.fromURL("./upload/"+f2, function(img) {
		img.set({
			 left: $x1, top: $y1,width: $h1,height: $w1,
		    borderColor: 'gray',
		    cornerColor: 'black',
		    cornerSize: 30,
		    transparentCorners: true,
		    rotatingPointOffset:100
			 	 
		})
		canvas.add(img);
		/*
	    canvas.add(new fabric.Text($text.val(), {
	      left: 200, top: 150,
	      fill: '#000000',
	      stroke: '#ffffff',
	      strokeWidth: 2,
	      fontFamily: 'Arial',
		  fontSize: 50,
          fontWeight: ($bold.is(':checked') ? 'bold' : null)
	    }));
	    */
	    

		canvas.renderAll();
	});

	 canvas.on({
		'touch:gesture': function () {
          //alert('touch');
          	var h1 = parseInt($('#h').val())*1.5;
  			var w1 = parseInt($('#w').val())*1.5;
			var obj = canvas.item(0);
	    	//obj.set({width: w1, height: h1});
		},
		'mouse:down': function () {
          //console.log('Mouse down');
		},
		'mouse:up': function () {
          //console.log('Mouse up');
		},
		'mouse:move': function (e) {
          //console.log(e);
		},
		'object:moving': function (e) {
			var e1 = e.e;
          //console.log(e1);
          //console.log("X=>"+e1.layerX);
          //console.log("Y=>"+e1.layerY);
          $("#xt").val(e1.x);
          $("#yt").val(e1.y);
		}
	});


//ケースを変更したら
	fabric.util.addListener(document.getElementById('casecolr'), 'change', function () {
	    var colors = $(this).val();
  		var path = "case/";
		canvas.setBackgroundImage(path+colors+"1.png", canvas.renderAll.bind(canvas), {
	            originX: 'left',
	            originY: 'top',
	            left: 0,
	            top: 0,
	            width:625,
	            height:625
	        });
		canvas.setOverlayImage(path+colors+"2.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625,
            selectable:false
        });
	    canvas.renderAll();
	});
	//画像関連
/*
	fabric.util.addListener(document.getElementById('shot'), 'click', function (e) {
	    var obj = canvas.item(0);
		obj.set({hasControls : false,hasBorders :false});
	    $('#w').val(obj.width*obj.scaleX);
	    $('#h').val(obj.height*obj.scaleY);
	    $('#x').val(obj.left);
	    $('#y').val(obj.top);
	    if(obj.angle){
	    	$('#rote').val(obj.angle);
	    }else{
	    	$('#rote').val(0);
	    }

		canvas.renderAll();
	});
*/
	//画像出力時
	/*
	fabric.util.addListener(document.getElementById('reedit'), 'click', function (e) {
		//編集可能状態を消す
		var obj = canvas.item(0);
		obj.set({selectable:false,active:false});
		//obj1.set({selectable:false,active:false});
		canvas.renderAll();
	});
	*/
	//再オーバーレイ
/*
	fabric.util.addListener(document.getElementById('reedit2'), 'click', function (e) {
		var obj = canvas.item(0);
		obj.set({selectable:false,active:false});
		canvas.setOverlayImage(null, canvas.renderAll.bind(canvas));
		canvas.setOverlayImage(path+"bg2.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625,
            selectable:false
        });
		canvas.renderAll();
			//console.log("start");

		
		
					var cnf = $j("#cnf").val();
			    	 var img2=new Image();
			    	var type = 'image/png';  
			    	img2.src = canvas.toDataURL(type);
			    	//$j("#img2").val(img2.src);
					$j.post("./convert.php?mode=img2", {
						img2:img2.src,cnf:cnf
					}).done(function(res) {
						console.log("img2=>ok");
					});
		
		
	});
*/
	//再オーバーレイを戻す
/*
	fabric.util.addListener(document.getElementById('reedit3'), 'click', function (e) {
		canvas.setOverlayImage(path+f1+"2.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625,
            selectable:false
        });
		canvas.renderAll();
		console.log("reedit3-end");
	});
*/
	fabric.util.addListener(document.getElementById('slide'), 'click', function (e) {
		//console.log(canvas.getActiveObject());
		var size = parseInt($("#slide").val())*5;
		var h1 = parseInt($('#h').val());
  		var w1 = parseInt($('#w').val());
		var ww = <?php echo $upwsize; ?>+size;
		var hh = <?php echo $uphsize; ?>+size;
		var xx = $('#x').val();
		var yy = $('#y').val();

		var obj = canvas.item(0);
	    obj.set({width:ww, height:hh});
	    if(ww < 0) ww = 0;
	    if(hh < 0) hh = 0;
	    $('#w').val(ww);
	    $('#h').val(hh);
	    $('#x').val(xx);
	    $('#y').val(yy);
	    
	    canvas.renderAll();
	});
	

	//slider
			$("#slider").slider({
				orientation: "horizontal",
				range: "min",
				max: 100,
				value: 50,
				slide: refreshSwatch,
				change: refreshSwatch
			});
			$("#slider").slider("value", 50);

	var ua = navigator.userAgent;
    if(ua.indexOf('iPhone') > 0 || ua.indexOf('iPod') > 0 || ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0){
        $("#details").css("display","none");
    }

	//toggle
	testObj = new Object();
	testObj.duration = 3000;
	
	
	$("#strbtn").on("click",function(){
		$('.str-box').slideToggle({ distance: 500 });
	});
	/*
	$("#str1").on("click",function(){
		$("#text").trigger("click");
		$("#fontsize").trigger("keyup");
		$("#fontfamilies").trigger("change");
		$("#bold").trigger("click");
		$("#strokeWidth").trigger("keyup");
		
		$('.str-box').slideToggle({ distance: 500 });
	});
	*/
	$("#views1").on("click",function(){
		if($(this).html()=="確 定"){
			$(this).html("プレビュー");
			$("#slider").css("z-index","0");
			$(".navbar-toggle").css("display","none");
		}else{
			$(this).html("確 定");
			$("#slider").css("z-index","10");
			$(".navbar-toggle").css("display","block");
		}
		
		$('#details').slideToggle({ distance: 500 });
	});

});
	
		function refreshSwatch() {
			var slider = $("#slider").slider("value");
			var vals = parseInt(slider) - 50;
			if (w === undefined) {
			}
			if (w) {
				$("#slide").val(vals);
				$("#slide").trigger("click");
			}
		}
		function Tops(){
	        $('body,html').animate({
	            scrollTop: 0
	        }, 500);
	        return false;
		}
	
</script>

<script type="text/javascript" src="js/jquery.excolor.js"></script>
</body>
</html>
