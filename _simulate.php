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
	$upwsize = 500;
	$uphsize = round($imgheight/($imgwidth/500));
}

//数量リストボックス
$value = array();
for($i=1;$i<=99;$i++){
	$value[] = array("key"=>$i);
}

//初期値
$cases1 = "<option value=\"\">選択してください。</option>";
foreach($case_list as $key => $val){
	if($key == "iPhone7"){$sel="selected";}else{$sel="";}
	$cases1 .= "<option value=\"".$key."\" ".$sel.">".$key."</option>";
}
$color1 = "<option value=\"\">選択してください。</option>";
foreach($case_list["iPhone7"] as $key => $val){
	if($key == "gold"){$sel="selected";}else{$sel="";}
	$color1 .= "<option value=\"".$key."\" ".$sel.">".$color_list[$key]."</option>";
}
$type1 = "<option value=\"\">選択してください。</option>";
foreach($case_list["iPhone7"]["gold"] as $key => $val){
	if($key == "gold"){$sel="selected";}else{$sel="";}
	$type1 .= "<option value=\"".$key."\" ".$sel.">".$val[0]."</option>";
}
$price1 = $case_list["iPhone7"]["gold"][0][1];
$price2 = number_format($case_list["iPhone7"]["gold"][0][1]);


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
<title>ケース作成画面</title>
<meta content="authenticity_token" name="csrf-param">
<meta content="width=device-width, initial-scale=1" name="viewport">
<link href="css/basic_case.css" media="all" rel="stylesheet">
<link rel="stylesheet" href="css/excolor.css" type="text/css" />
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<script data-turbolinks-track="true" src="js/tn-menu.js"></script>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

<!--script src="js/fabric.min.js"></script-->
<script src="js/fabric.js"></script>
<script src="js/jquery.alphaimage.js"></script>

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
    <h1>オリジナルケース作成画面</h1>
    

    <!--form action="#" id="orderForm" enctype="" method="post" accept-charset="utf-8"-->
      <div class="row devise" id="phone_case">
        <div class="col-md-7">
          <div id="simulate-box">
            <div id="simulate-wrap">
              <!-- 機種ごとに合成部分の処理をわける -->
              
              <!-- 画像の合成部分の処理-->
              	
              <canvas id="myCanvas" width="625" height="625"></canvas>
              <div id="slider"></div>
              	  
              	  
              <!--input type="button" name="reimg" id="reimg" value="元に戻す">
              <input type="button" name="strbtn" id="strbtn" value="文字入力"-->
              <!-- メニューの処理-->
              <!-- 文字入力-->
              
              <div class="str-box" >
               	 <div class="section">
               	  	<p>テキスト</p>
				    <textarea id="text" class="strs"></textarea>
				  </div>
					
				<div class="section">
				    <p>フォントサイズ</p>
				    <input type="text" value="50" id="fontsize" class="strs">
				  </div>
				  <div class="section">
				    <p>縁の色</p><input type="text" value="#000000" id="fill-color" class="strs"><br />
				    <p>テキスト色</p><input type="text" value="#ffffff" id="stroke-color" class="strs">
				  </div>
					
					<div class="section">
				    <p>縁の太さ</p><input type="text" value="2" id="strokeWidth" class="strs">
				  </div>
				  <div class="section"><p>フォント</p>
				    <select id="fontfamilies" class="strs">
				      <?php echo $font_list; ?>
				    </select>
				  </div>
				  <div class="section">
				    <label for="bold"><input type="checkbox" id="bold" class="strs"> <b style="color:#000;">太字</b></label>
				  </div>
				</div>
				
              　<!--文字入力-->
              
              

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
          	<!--h2><i class="fa fa-cog"></i>ケース名</h2>
          	<div class="row">
                <div class="col-md-12">
                    <input class="form-control" id="product_name" name="product_name" placeholder="ケース名を入力" type="text" />
                    <div id="errors1" class="errors" style="color:red;"></div>
                </div>
              </div-->
          	
          	<h2><i class="fa fa-cog"></i>iPhoneの機種を選択</h2>


            <form accept-charset="UTF-8" action="#" class="edit_contents" id="edit_contents" method="post">
            	<div style="margin:0;padding:0;display:inline"><input name="_method" type="hidden" value="patch" />
            	<input name="authenticity_token" type="hidden" value="/OkmKJNNIkWqyVq7NgETUX0bTxWqpUPdXwWYBR7q1xQ=" /></div>
              <div class="row">
                <div class="col-md-12">
                    
	                <select class="form-control order-key" id="casesample" name="">
	                	<option value="">選択してください。</option>
						<?php foreach($case_list as $key=> $val){ echo "<option value=\"".$key. "\">".$key."</option>"; } ?>
	                </select>
                    <div id="errors1" class="errors" style="color:red;"></div>
                </div>
              </div>
            </form>

              <!-- 機種ごとにメニューをわける -->
              <!-- 画像の合成部分の処理-->
              <h2><i class="fa fa-cog"></i>iPhone本体の色を選択</h2>
              	  
	          	<div class="row">
	                <div class="col-md-12">
		                <select class="form-control order-key" id="casecolr" name="casecolr">
			                	<option value="">選択してください。</option>
							
		                </select>
		             </div>
		          </div>
              <h2><i class="fa fa-cog"></i>ケースの素材を選択</h2>
          	<div class="row">
                <div class="col-md-12">
                <select class="form-control order-key" id="casetype" name="casetype">
	                	<option value="">選択してください。</option>
					
                </select>
		             </div>
		          </div>
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
              <p class="aleart">
                「ケースつくーる」では、ケース側面への印刷を行いません。素材は、TPUもしくは、ポリカーボネートのどちらかを選択頂けます。色は透明となります。<br>
<br>
※TPUケースは、このように柔らかい素材となります。<br>
※<a href="images/2017-05-03_162524.png" target="_blank">画像確認はこちら</a></p>


              <div class="order_option">
					<p><input type="hidden" name="chkon" id="chkon" value="on"></p>
                <button class="btn btn-lg btn-primary btn-wide btn-convert" id="shot" onclick="return false;">注文する</button>
					<!--div style="display:none;" id="loader"><img src="loading_6.gif" style="width:100px;"></div-->
					<!--p class="downloadButton"><a id="download0" href="./convert/test2.zip" download="test.png"><input type="button" value="ダウンロード"></a></p-->
					<!--div style="display:none;" class="downloadButton" id="janimg"></div>
					<input type="text" name="jan" id="jan" value="" style="width:150px;" class="downloadButton" onclick="this.select()"-->
					<input type="hidden" name="cnf" id="cnf" value="<?php echo date('YmdHis'); ?>" >
              </div>
              
        </div><!-- / col-->
    </div>    

    <!--/form-->
	<div id="result" style="display:none;"></div>
	    <p class="attention">
<!--
	      <a href="#"><i class="fa fa-info-circle"></i> 表面のみ印刷スマホケースの素材・サイズ</a>
	      <br>
	      <a href="#"><i class="fa fa-info-circle"></i> 表面のみ印刷スマホケースのサンプルイメージ</a>
-->
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
			<input type="hidden" id="xt" name="xt" value="200">
			<input type="hidden" id="yt" name="yt" value="150">
			<input type="hidden" id="slide" name="slide" value="50">
			<input type="hidden" id="img2" name="img2" value="">
			<input type="hidden" id="rote" name="rote" value="">
			<input type="hidden" id="reedit" name="reedit" value="">
			<input type="hidden" id="product_name" name="product_name" value="">
	    </form>
	</div>    
	</section>
	<div id="modal-overlay">
		<div id="modal-content"><img src="./images/loadingcircle.gif"></div>
	</div>
			
			
<footer id="footer">
<div class="container">

    <nav>
<!--
        <ul class="row">
            <li><a href="#" target="_blank">よくある質問</a></li>
            <li><a href="#" target="_blank">利用ガイド</a></li>
            <li><a href="#">特定商取引法に基づく表示</a></li>
            <li><a href="#">プライバシーポリシー</a></li>
        </ul>
-->
    </nav>
    <div id="copyright">&#169;
        <span itemprop="name">ケースつくーる</span>
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
        $j("#casesample").change(function(){
        	var cases = $j(this).val();
			$j.post(
				"scp.php?mode=kishu",
				{ cases: cases })
				.done(function(res) {
					$j("#casecolr").html(res);
			});
        });
        $j("#casecolr").change(function(){
        	var cases = $j("#casesample").val();
        	var color = $j(this).val();
			$j.post(
				"scp.php?mode=color",
				{ cases: cases,color: color })
				.done(function(res) {
					$j("#casetype").html(res);
					var h = $j("#h").val();
			});
        });
        $j("#casetype").change(function(){
        	var cases = $j("#casesample").val();
        	var color = $j("#casecolr").val();
        	var value = $j("#value").val();
        	var type = $j(this).val();
			$j.post(
				"scp.php?mode=price",
				{ cases: cases,color: color,value:value,type:type })
				.done(function(res) {
					$j(".price").html(res);
					$j("#price").val(res);
					var h = $j("#h").val();
			});
        });
        $j("#value").change(function(){
        	var cases = $j("#casesample").val();
        	var color = $j("#casecolr").val();
        	var value = $j("#value").val();
        	var type = $j("#casetype").val();
			$j.post(
				"scp.php?mode=price",
				{ cases: cases,color: color,value:value,type:type })
				.done(function(res) {
					$j(".price").html(res);
					$j("#price").val(res);
			});
        });
        
		$j("#shot").click(function(){
			$j(".errors").html("");
			var value = $j("#value").val();
			var product_name = $j("#product_name").val();
			/*
			if(product_name == ""){
				$j("#errors1").html("ケース名を入力してください。");
				Tops();
				return false;
			}
			*/
			if(value == ""){
				$j("#errors2").html("数量を入力してください。");
				Tops();
				return false;
			}
			
			$j("#modal-overlay").css("display", "block");
			centeringModalSyncer();
			
			var canvas = document.getElementById("myCanvas");  //canvas要素を取得
			if (canvas.getContext) {
			    var  ctx = canvas.getContext('2d');
			    //アクティブな状態を消す
			    $j("#reedit").trigger("click");
			    
			    //色々な図形の描画など・・・・・
			    //図形の保存
			    var img=new Image();
			    var type = 'image/png';  
			    img.src = canvas.toDataURL(type);
			    
			    
			    
			    //console.log(img.src);
			    img.onload = function(){
			    	//location.href = img.src;
			    	//console.log(img.src);
					var chk = $j("[name=chkon]:checked").val();
					var cnf = $j("#cnf").val();
					var value = $j("#value").val();
					var product_name = $j("#product_name").val();
					var price = $j("#price").val();
					var status = $j("[name=status]:checked").val();
					var casesample = $j("#casesample").val();
					var x = $j("#x").val();
					var y = $j("#y").val();
					var h = $j("#h").val();
					var w = $j("#w").val();
					var rote = $j("#rote").val();
					var files = $j("#files").val();
					
					$j("#img2").trigger("click");
			    	var img2 = $j("#img2").val();
					
					$j.post("./convert.php?mode=img", {
						img: img.src,img2:img2,chk:chk,cnf:cnf,product_name:product_name,value:value,price:price,status:status,casesample:casesample,x:x,y:y,w:w,h:h,files:files,rote:rote
					}).done(function(res) {
						//console.log(res);
						
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
    	$("#casesample").html('<?php echo $cases1; ?>');
		$("#casecolr").html('<?php echo $color1; ?>');
		$("#casetype").html('<?php echo $type1; ?>');
		$(".price").html('<?php echo $price2; ?>');
		$("#price").val('<?php echo $price1; ?>');
	//});
	
	
	


$(function(){
  var maxWidth = 625
  var f1 = $("#casecolr").val();
  var f2 = "<?php echo $files; ?>";
  var type= $("#casetype").val();
  var cases = $("#casesample").val();
  var path = "case/"+cases+"/"+type+"/";
  
  var $text = $('#text');
  var $strokeColor=  $('#stroke-color');
  var $fillColor=  $('#fill-color');
  var $strokeWidth=  $('#strokeWidth');
  var $fontFamilies = $('#font-families');
  var $bold = $('#bold');
  var $fontsize = $('#fontsize');
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


	canvas.setBackgroundImage(path+f1+".png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625
        });

	canvas.setOverlayImage(path+f1+"_w.png", canvas.renderAll.bind(canvas), {
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
		
	    canvas.add(new fabric.Text($text.val(), {
	      left: 200, top: 150,
	      fill: '#000000',
	      stroke: '#ffffff',
	      strokeWidth: 2,
	      fontFamily: 'Arial',
		  fontSize: 50,
          fontWeight: ($bold.is(':checked') ? 'bold' : null)
	    }));
	    
	    

		canvas.renderAll();
	});

	 canvas.on({
		'touch:gesture': function () {
          //alert('touch');
          	var h1 = parseInt($('#h').val())*1.5;
  			var w1 = parseInt($('#w').val())*1.5;
			var obj = canvas.item(0);
	    	obj.set({width: w1, height: h1});
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
          //console.log(e);
		}
	});


//ケースを変更したら
	fabric.util.addListener(document.getElementById('casecolr'), 'change', function () {
	    var colors = $(this).val();
	    var cases = $("#casesample").val();
  		var type= $("#casetype").val();
  		var path = "case/"+cases+"/"+type+"/";
		canvas.setBackgroundImage(path+colors+".png", canvas.renderAll.bind(canvas), {
	            originX: 'left',
	            originY: 'top',
	            left: 0,
	            top: 0,
	            width:625,
	            height:625
	        });
		canvas.setOverlayImage(path+colors+"_w.png", canvas.renderAll.bind(canvas), {
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
	fabric.util.addListener(document.getElementById('shot'), 'click', function (e) {
	    var obj = canvas.item(0);
		//console.log(obj);
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
	//画像出力時
	fabric.util.addListener(document.getElementById('reedit'), 'click', function (e) {
		//編集可能状態を消す
		var obj = canvas.item(0);
		var obj1 = canvas.item(1);
		obj.set({selectable:false,active:false});
		obj1.set({selectable:false,active:false});
		canvas.renderAll();
	});
	
	
	
	fabric.util.addListener(document.getElementById('img2'), 'click', function (e) {
		
		canvas.setBackgroundImage(null, canvas.renderAll.bind(canvas));
		canvas.setOverlayImage(null, canvas.renderAll.bind(canvas));
		
		var type= $("#casetype").val();
		var cases = $("#casesample").val();
		var path = "case/"+cases+"/"+type+"/";
		var colors = $("#casecolr").val();
		

/*
		console.log(path+"w2.png");
		canvas.setOverlayImage(path+"w2.png", canvas.renderAll.bind(canvas), {
            originX: 'left',
            originY: 'top',
            left: 0,
            top: 0,
            width:625,
            height:625,
            selectable:false
        });
        canvas.renderAll();
*/
		var type = 'image/png';  
		$("#img2").val(canvas.toDataURL(type));
		
		
		//console.log(path+colors+"_w.png");
		canvas.setOverlayImage(null, canvas.renderAll.bind(canvas));
		//元のキャンバスの状態に戻す
		canvas.setBackgroundImage(path+colors+".png", canvas.renderAll.bind(canvas), {
	            originX: 'left',
	            originY: 'top',
	            left: 0,
	            top: 0,
	            width:625,
	            height:625
	        });
		
		canvas.setOverlayImage(path+colors+"_w.png", canvas.renderAll.bind(canvas), {
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
	
	
	
	//元に戻す
	fabric.util.addListener(document.getElementById('reimg'), 'click', function (e) {
	    var obj1 = canvas.item(1);
	    obj1.remove();
	    var obj = canvas.item(0);
	    obj.remove();
	    //obj.set({width: 250, height: 250,top:150,left:150,angle:0,hasControls : true,hasBorders :true,scaleX:1,scaleY:1});
	    //console.log("reimg");
	    $('#w').val("250");
	    $('#h').val("250");
	    $('#x').val("150");
	    $('#y').val("150");
	    $('#sile').val("0");
	    $("#slider").slider("value", 50);

		fabric.Image.fromURL("./upload/"+f2, function(img) {
			img.set({
				 left: $x1, top: $y1,width: $h1,height: $w1 
			})
			canvas.add(img);
			canvas.item(0).set({
			    borderColor: 'gray',
			    cornerColor: 'black',
			    cornerSize: 30,
			    transparentCorners: true,
			    rotatingPointOffset:100
			  });
			    
		    canvas.add(new fabric.Text("", {
		      left: 200, top: 150,
		      fill: '#000000',
		      stroke: '#ffffff',
		      strokeWidth: 2,
		      fontFamily: 'Arial',
			  fontSize: 50,
	          fontWeight: ($bold.is(':checked') ? 'bold' : null)
		    }));
			    
		});
	    
	    canvas.renderAll();
	});

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
	    obj.set({width: ww, height: hh});
	    if(ww < 0) ww = 0;
	    if(hh < 0) hh = 0;
	    $('#w').val(ww);
	    $('#h').val(hh);
	    $('#x').val(xx);
	    $('#y').val(yy);
	    
	    canvas.renderAll();
	});
	
	//テキストフォーム変更関連
	fabric.util.addListener(document.getElementById('text'), 'keyup', function (e) {
		var obj = canvas.item(1);
		var txt = e.target.value.replace(/\s+/g, "");
	    obj.setText(txt);
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('text'), 'click', function (e) {
		var obj = canvas.item(1);
		var txt = e.target.value.replace(/\s+/g, "");
	    obj.setText(txt);
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('fontsize'), 'keyup', function (e) {
		var obj = canvas.item(1);
		var fsize = $('#fontsize').val();
		obj.set({fontSize: fsize});
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('fontfamilies'), "change", function (e) {
		var obj = canvas.item(1);
		var font = $('#fontfamilies').val();
		obj.set({fontFamily: font});
		console.log(font);
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('stroke-color'), 'click', function (e) {
		var obj = canvas.item(1);
		var color1 = $('#stroke-color').val();
		obj.set({fill: color1});
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('fill-color'), 'click', function (e) {
		var obj = canvas.item(1);
		var color2 = $('#fill-color').val();
		obj.set({stroke: color2});
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('bold'), 'click', function (e) {
		var obj = canvas.item(1);
		var bold = $("#bold").is(':checked') ? 'bold' : null
		obj.set({fontWeight: bold});
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('strokeWidth'), 'keyup', function (e) {
		var obj = canvas.item(1);
		var strokeWidth = $('#strokeWidth').val();
		obj.set({strokeWidth: strokeWidth});
	    canvas.renderAll();
	});
	
	//文字入力をタップしたとき文字をactiveにする/////////////////////////////////////////////
	fabric.util.addListener(document.getElementById('strbtn'), 'click', function (e) {
		
		var obj1 = canvas.item(0);
		if(obj1.visible == true){
			obj1.set({active: false,visible: false});
			
		}else{
			obj1.set({active: true,visible: true});
		}
		canvas.setActiveObject(canvas.item(1));
	    canvas.renderAll();
	});
	fabric.util.addListener(document.getElementById('str1'), 'click', function (e) {
		var obj1 = canvas.item(0);
		
		if(obj1.visible == true){
			obj1.set({active: false,visible: false});
			
		}else{
			obj1.set({active: true,visible: true});
		}
		canvas.setActiveObject(canvas.item(1));
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
	$("#str1").on("click",function(){
		$("#text").trigger("click");
		$("#fontsize").trigger("keyup");
		$("#fontfamilies").trigger("change");
		$("#bold").trigger("click");
		$("#strokeWidth").trigger("keyup");
		
		$('.str-box').slideToggle({ distance: 500 });
	});
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
