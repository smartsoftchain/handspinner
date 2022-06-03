<?php
header("Content-Type: text/html; charset=utf-8");
header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
}

//携帯枠画像
$waku_list=array(
"iPhone7hardcase_black_touka",
"iPhone7hardcase_clear_touka",
"iPhone7hardcase_white_touka",
"iPhone7taishougeki_clear_touka",
"iPhone7TPUcase_clear_touka",
"iPhone6hardcase_black_touka",
"iPhone6hardcase_clear_touka",
"iPhone6hardcase_white_touka",
"iPhone6metallicTPU_gold_touka",
"iPhone6metallicTPU_pink_touka",
 ); 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>画像アップロード | オリジナルグッズを1個から業界最安値で作成　Canvath/キャンバス</title>
<meta content="authenticity_token" name="csrf-param">
<meta content="width=device-width, initial-scale=1" name="viewport">
<link href="css/basic_case.css" media="all" rel="stylesheet">
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

<script data-turbolinks-track="true" src="js/tn-menu.js"></script>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="js/script.js"></script>
<script src="js/jquery.alphaimage.js"></script>
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
          <li><a class="hatena" href="#" target="_blank"><i class="fa fa-question-circle"></i> よくある質問</a></li>
          <li><a class="about" href="#" target="_blank"><i class="fa fa-file-text-o"></i> 利用ガイド</a></li>
        </ul>
 	  </div>
               
    </div>
</div>
</header>
  
<section id="contents_simulate">
  <div class="container">
    <span class="cle"><i class="fa fa-picture-o"></i></span>
    <h1>表面のみ印刷スマホケース画像</h1>
    

    <form action="#" id="orderForm" enctype="" method="post" accept-charset="utf-8">
      <div class="row devise" id="phone_case">
        <div class="col-md-7">
          <div id="simulate-box">
            <div id="simulate-wrap">
              <!-- 機種ごとに合成部分の処理をわける -->
              
              <!-- 画像の合成部分の処理-->
              	
              <div id="simulate">
              	  
            	
              	<div id="lines"><img alt="" height="auto" id="naka" src="./images/naka.png" width="auto" /></div>
                <div id="img_bg">
                  <div id="re"><img alt="" height="auto" id="uploadImage" src="./upload/<?php echo $files;?>" width="auto" /></div>
                </div>
                <div id="itemView" class=""><img alt="" height="auto" id="case" src="" width="auto" /></div>
                 <div id="shots">	</div>
              </div><!-- /simulate -->
              <!-- メニューの処理-->

              <div id="slider"></div>
              
            </div><!-- /simulate-wrap -->
          </div><!-- /simulate-box -->
        </div><!-- /col-->


        <div class="col-md-4 detail">
          	<h2><i class="fa fa-cog"></i>画像タイトル</h2>


            <form accept-charset="UTF-8" action="#" class="edit_contents" id="edit_contents" method="post"><div style="margin:0;padding:0;display:inline"><input name="_method" type="hidden" value="patch" /><input name="authenticity_token" type="hidden" value="/OkmKJNNIkWqyVq7NgETUX0bTxWqpUPdXwWYBR7q1xQ=" /></div>
              <div class="row">
                <div class="col-md-12">
                    <input class="form-control" id="contents_name" name="contents[name]" placeholder="タイトル名を入力" type="text" />
                </div>
              </div>
            </form>

              <!-- 機種ごとにメニューをわける -->
              <!-- 画像の合成部分の処理-->
              <h2><i class="fa fa-cog"></i>機種を選択</h2>
                <select class="form-control order-key" id="casesample" name="">
					<?php foreach($waku_list as $key=> $val){ echo "<option value=\"".$val. "\">".$val."</option>"; } ?>
                </select>
              <p class="commodity_price">
                ¥<strong class="price">900</strong>
                <span>(税込)</span>
              </p>
              <p class="aleart">
                表面のみ印刷スマホケースは側面への印刷を行わないため、側面部分はポリカーボネート樹脂（プラスティック）の白色か透明となります。<br>
                なお、赤色の線まで画像データが届いていない箇所も、印刷を行わないため素材の白色か透明のままとなりますのでご注意ください。
              </p>


              <div class="order_option">
					<p><input type="checkbox" name="chkon" id="chkon" value="on">バーコードを作成する</p>
                <a class="btn btn-lg btn-primary btn-wide btn-convert" href="javascript:void(0);" onclick="screenshot('#simulate');" id="shot">保存する</a>
					<div style="display:none;" id="loader"><img src="loading_6.gif" style="width:100px;"></div>
					<p class="downloadButton"><a id="download0" href="./convert/test2.zip" download="test.png"><input type="button" value="ダウンロード"></a></p>
					<div style="display:none;" class="downloadButton" id="janimg"></div>
					<input type="text" name="jan" id="jan" value="" style="width:150px;" class="downloadButton" onclick="this.select()">
					<input type="hidden" name="cnf" id="cnf" value="<?php echo date('YmdHis'); ?>" >
              </div>
              
        </div><!-- / col-->
	</div>
    </form>

    <p class="attention">
      <a href="#"><i class="fa fa-info-circle"></i> 表面のみ印刷スマホケースの素材・サイズ</a>
      <br>
      <a href="#"><i class="fa fa-info-circle"></i> 表面のみ印刷スマホケースのサンプルイメージ</a>
    </p>

    <form accept-charset="UTF-8" action="" data-no-turbolink="true" id="convert_form" method="post"><div style="margin:0;padding:0;display:inline"><input name="authenticity_token" type="hidden" value="/OkmKJNNIkWqyVq7NgETUX0bTxWqpUPdXwWYBR7q1xQ=" /></div>
        <input id="devise" name="devise" type="hidden" />
        <input id="key" name="key" type="hidden" />
        <input id="type" name="type" type="hidden" />
        <input id="cut" name="cut" type="hidden" />
        <input id="printPosition" name="printPosition" type="hidden" />
        <input id="size" name="size" type="hidden" />
        <input id="width" name="width" type="hidden" />
        <input id="height" name="height" type="hidden" />
        <input id="top" name="top" type="hidden" />
        <input id="left" name="left" type="hidden" />
        <input id="name" name="name" type="hidden" />
        <input id="orientation" name="orientation" type="hidden" />
		<input type="hidden" name="wresult" id="wresult" value="0">
		<input type="hidden" id="hresult" name="hresult" value="0">
    </form>
    
</div>
</section>

<footer id="footer">
<div class="container">

<img src="images/img001_flogo.png" />
    <nav>
        <ul class="row">
            <li><a href="#" target="_blank">よくある質問</a></li>
            <li><a href="#" target="_blank">利用ガイド</a></li>
            <li><a href="#">特定商取引法に基づく表示</a></li>
            <li><a href="#">プライバシーポリシー</a></li>
        </ul>
    </nav>
    <div id="copyright">&#169;
        <span itemprop="name">company </span>
        <span itemprop="copyrightYear"></span>
    </div>
    
  </div>
</footer>


		<div id="output_screen">
			<img id="screen_image" class="alphaImage">
		</div>
		<div id="output_screen2">
			<img id="screen_image2" class="alphaImage">
		</div>
<div class="item" style="display:none;">
    <div class="item_loading"></div>
</div>

</body>
</html>
