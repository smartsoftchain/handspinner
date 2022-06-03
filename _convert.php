<?php


$path = "./inc";
	require_once($path."/conf.php");
	require_once($path."/my_db.inc");
	require_once($path."/htmltemplate.inc");
	require_once($path."/errlog.inc");
	

$DB_URI = array("host" => $DB_SV, "db" => $DB_NAME, "user" => $DB_USER, "pass" => $DB_PASS);

define("SCRIPT_ENCODING", "UTF-8");
// データベースの漢字コード
define("DB_ENCODING", "UTF-8");
// メールの漢字コード(UTF-8かJIS)
define("MAIL_ENCODING", "JIS");

exec("find ./upload/ -type f -mtime +1 -exec rm -fv {} \;");
exec("find ./log/ -type f -mtime +1 -exec rm -fv {} \;");

$site_url = "http://203.189.96.217/casecasecase/";
	
		$name = "waku";
		$name2 = "gousei";
		$name3 = "haikei";
		
		$cnf = $_REQUEST["cnf"];
		$chk = $_REQUEST["chk"];
		//$chk = "on";
		$value = $_REQUEST["value"];
		$product_name = $_REQUEST["product_name"];
		$status = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : 3;
		$price = (isset($_REQUEST['price'])) ? $_REQUEST['price'] : 0;
/*
name →　（必須）商品名
product_code　→　（必須）商品コード
price02　→　（必須）商品税抜き価格（カンマなどいれないint型でお願い致します。）
quantity →　（必須）数量（カンマなどいれないint型でお願い致します。）
main_image　→　（必須）完成イメージのhttp:から始まるパス
sub_image1　→　合成画像のhttp:から始まるパス
sub_image2　→　背景画像のhttp:から始まるパス
sub_image3　→　バーコード画像のhttp:から始まるパス
sub_image4　→　その他、画像が増えた分の枠
*/
//$product_name = "テスト商品名";
//$jan = "123456789";


	if($_REQUEST["mode"] == "img"){
		$canvas = $_REQUEST["img"];
		
		$files = $_REQUEST["files"];//元画像ファイル名
		$size = (int)str_replace("px","",$_REQUEST["size"]);//WEB上の画像サイズ
		$left = (int)str_replace("px","",$_REQUEST["left"]);//WEB上の画像左位置
		$top = (int)str_replace("px","",$_REQUEST["top"]);//WEB上の画像上位置
		list($imgwidth, $imgheight) = getimagesize('./upload/'.$files);
		//倍率計算
		$rate = round($imgwidth/$size,3);
		
			
		//JAN取得だったらファイル名をJANに
		if($chk == "on"){
			$jan = GetJan($cnf);
			$name = $jan."_".$name;
			$name2 = $jan."_".$name2;
			$name3 = $jan."_".$name3;
		}else{
			$name = $cnf."_".$name;
			$name2 = $cnf."_".$name2;
			$name3 = $cnf."_".$name3;
		}
		
		//$name3 = "tests";
		//背景画像を元画像から切り出し
		/*if($size < $imgwidth){*/
			//if($size > 200){
				if($left > 0){
					
					if(($left-50) > 0){
						$xx = (($left-50)*$rate);
						$x = 0;
					}else{
						$xx = 0;
						$x = (50-abs($left))*$rate;
					}
				}else{
					$x = ((abs($left)+50)*$rate);
					//$x = (($left*-1)*$rate);
					$xx = 0;
				}
				if($top > 0){
					$y = 0;
					$yy = $top*$rate;
				}else{
					$y = ($top*-1)*$rate;
					$yy = 0;
				}
				$x2 = (200*$rate)+$x;
				$y2 = $y+(401*$rate);
				
				$scp = "/usr/local/bin/convert -crop ".$x2."x".$y2."+".$x."+".$y." ./upload/".$files." ./convert/".$name3."_1.png";
				exec($scp);
				
				@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);
				//元画像サイズからの白紙の画像を作成
				$scp = "/usr/local/bin/convert -size ".(200*$rate)."x".((401*$rate))." xc:white ./convert/".$name3."_base.png";
				exec($scp);
				@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);

				$scp = "/usr/local/bin/convert ./convert/".$name3."_base.png ./convert/".$name3."_1.png -gravity northwest -geometry +".$xx."+".$yy." -compose over -composite ./convert/".$name3."_1.png";
				exec($scp);
				
			//}
		@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", "size=>".$size."■left=>".abs($left)."■top=>".$top."■rate=>".$rate."■imgwidth=>".$imgwidth."■imgheight=>".$imgheight."■height=>".$height."\n".$scp."\n", FILE_APPEND);

		
		//ヘッダに「data:image/png;base64,」が付いているので、それは外す
		$canvas = preg_replace("/data:[^,]+,/i","",$canvas);
		 
		//残りのデータはbase64エンコードされているので、デコードする
		$canvas = base64_decode($canvas);
		//まだ文字列の状態なので、画像リソース化
		$image = imagecreatefromstring($canvas);
		 
		//画像として保存（ディレクトリは任意）
		imagesavealpha($image, TRUE); // 透明色の有効
		imagepng($image ,'./convert/'.$name.'.png');
		
		//exec("cp ./convert/".$name.".png ./convert/".$name."_2.png");
		//画像合成
		if(file_exists("./convert/".$name.".png")){
			//ケース画像をトリミング
			$scp = "/usr/local/bin/convert -crop 200x400+50+50 ./convert/".$name.".png ./convert/".$name.".png";
			exec($scp);
			$scp = "/usr/local/bin/convert ./convert/".$name.".png -trim ./convert/".$name.".png";
			exec($scp);
			
			if($_REQUEST["type"] == "2"){
				
				$scp = "/usr/local/bin/convert ./convert/".$name.".png -resize 200% ./convert/resize.png";
				exec($scp,$res);
			}else{
				$scp = "/usr/local/bin/convert ./convert/".$name.".png -resize 150% ./convert/resize.png";
				exec($scp,$res);
			}
			$scp = "/usr/local/bin/convert ./images/haikei2.png ./convert/resize.png -gravity northwest -geometry +827+315 -compose over -composite ./convert/".$name2.".png";
			exec($scp,$res);
		}
	}elseif($_REQUEST["mode"] == "img2"){
		@unlink("./convert/test2.png");
		exec("rm -rf ./convert/test2.png");
		$canvas = $_REQUEST["img2"];
		$casesample = $_REQUEST["casesample"];
		//$chk = $_REQUEST["chk"];
		$jan = "";
		//JAN取得だったらファイル名をJANに
		if($chk == "on"){
			$jan = GetJan2($cnf);
			$name3 = $jan."_".$name3;
			$name = $jan."_".$name;
			$name2 = $jan."_".$name2;
			$zipname = $jan.".zip";
			copy("./img/".$jan."_jancode.png","./convert/".$name3.".png");
			exec("cp ./img/".$jan."_jancode.png ./convert/".$jan."_jancode.png");
			$jan_str = $jan;
		}else{
			$jan = $cnf;
			$name3 = $cnf."_".$name3;
			$name = $cnf."_".$name;
			$name2 = $cnf."_".$name2;
			$zipname = $cnf.".zip";
			
		}
		
		if(!file_exists('./convert/'.$name3.'_1.png')){
			//ヘッダに「data:image/png;base64,」が付いているので、それは外す
			$canvas = preg_replace("/data:[^,]+,/i","",$canvas);
			 
			//残りのデータはbase64エンコードされているので、デコードする
			$canvas = base64_decode($canvas);
			//まだ文字列の状態なので、画像リソース化
			$image = imagecreatefromstring($canvas);
			//$image = imagerotate($image, 90, 0);
			//画像として保存（ディレクトリは任意）
			imagesavealpha($image, TRUE); // 透明色の有効
			imagepng($image ,'./convert/'.$name3.'.png');
			//exec("cp ./convert/".$name3.".png ./convert/".$name3."_2.png");
		}else{
			exec("mv ./convert/".$name3."_1.png ./convert/".$name3.".png");
			//exec("cp ./convert/".$name3."_1.png ./convert/".$name3.".png");
		}
		//$scp = "convert ./convert/".$name3.".png -trim ./convert/".$name3.".png";
		//exec($scp);
		//リサイズ
		//背景画像をリサイズして回転
		$scp = "/usr/local/bin/convert -resize 197x394 ./convert/".$name3.".png ./convert/".$name3."_re.png";
		exec($scp);
		//回転
		@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);
		$scp = "/usr/local/bin/convert ./convert/".$name3."_re.png -rotate 270 ./convert/".$name3.".png";
		exec($scp);
		
		//リサイズした画像に枠画像をはめ込んでみるテスト
		if(file_exists("./files/".$casesample.".png")){
			list($width2, $height2) = getimagesize("./convert/".$name3."_re.png");
			//枠をリサイズ
			$scp = "/usr/local/bin/convert -resize ".$width2."x".$height2." ./files/".$casesample.".png ./convert/".$name."_re2.png";
			exec($scp);
			$scp = "/usr/local/bin/convert ./convert/".$name3."_re.png ./convert/".$name."_re2.png -composite ./convert/".$name.".png";
			exec($scp,$res);
		}
		
		@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n\n", FILE_APPEND);
		
		@unlink("./convert/".$name3."_re.png");
		@unlink("./convert/".$name."_re2.png");
		@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", "./files/".$casesample.".png"."\n\n", FILE_APPEND);
		
		
		
		chdir("convert");
		$zip = new ZipArchive();
		$res = $zip->open($zipname, ZipArchive::CREATE);
		 // DoWriteモード
		//$zipfile->setDoWrite();
		// zipファイルのオープンに成功した場合
		if ($res === true) {
		 
		    // 圧縮するファイルを指定する
		    $zip->addFile($name.'.png');
		    $zip->addFile($name2.'.png');
		    $zip->addFile($name3.'.png');
		    if($chk == "on"){
		    	$zip->addFile($jan."_jancode.png");
		    }
		    // ZIPファイルをクローズ
		    $zip->close();
		}
		
		
		$waku_url = $site_url."convert/".$name.'.png';
		$gosei_url = $site_url."convert/".$name2.'.png';
		$haikei_url = $site_url."convert/".$name3.'.png';
		$jan_url = $site_url."convert/".$jan."_jancode.png";
		
				$datas = array(
					"name"=>$product_name,
					"product_code"=>$jan,
					"price02"=>$price,
					"quantity"=>$value,
					"status"=>$status,
					"main_image"=>$waku_url,
					"sub_image1"=>$gosei_url,
					"sub_image2"=>$haikei_url,
					"sub_image3"=>$jan_url,
					"sub_image4"=>"",
				);
				@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", implode("■",$datas)."\n", FILE_APPEND);
		$result = PaymentPoast($datas);
		if($chk == "on"){
			//$result .= "<p class=\"downloadButton\"><a id=\"download0\" href=\"./convert/".$zipname."\" download=\"".$zipname."\"><input type=\"button\" value=\"ダウンロード\"></a></p>";
			$result .= "<div style=\"display:block;margin-top:10px;\" class=\"downloadButton\" id=\"janimg\"><img src=\"./img/".$jan."_jancode.png\"></div>";
			$result .= "<input type=\"text\" name=\"jan\" id=\"jan\" value=\"".$jan."\" style=\"width:150px;\" class=\"downloadButton\" onclick=\"this.select()\">";
		}
		
	$inst = DBConnection::getConnection($DB_URI);
		//DBに登録
		$code = "";
		if($chk == "on"){
			$code = $jan."_jancode.png";
		}
		$sql = "INSERT INTO `rireki` (`jan`, `date`, `title`, `case_title`, `price`, `value`, `total`, `gosei`, `haikei`, `case`, `code`, `zip`, `regist`)
VALUES ('".$jan_str."', '".$cnf."', '".$product_name."', '".$casesample."', '".$price."', '".$value."', '".($price*$value)."', '".$name2.'.png'."', '".$name3.'.png'."', '".$name.'.png'."', '".$code."', '".$zipname."', now());";
		$ret = $inst->search_sql($sql);
		
		echo $result."■".$zipname;
		
		
	}elseif($_REQUEST["mode"] == "jan"){
		$inst = DBConnection::getConnection($DB_URI);
		//ランダムに1件取得し、janコードを返す
		$sql = "select * from `jan` where `status`=0 order by rand() limit 1";
		$ret = $inst->search_sql($sql);
		if($ret["count"] > 0){
			$jan = $ret["data"][0];
			//このJANを使用済みにする
			$sqlu = "update `jan` set `status`=1 where `id`='".$jan["id"]."'";
			$retu = $inst->db_exec($sqlu);
			echo $jan["jan"];
		}
		exit;
	}
	
	
	function GetJan($cnf){
		global $DB_URI;
		$inst = DBConnection::getConnection($DB_URI);
		//ランダムに1件取得し、janコードを返す
		$sql = "select * from `jan` where `status`=0 order by rand() limit 1";
		$ret = $inst->search_sql($sql);
		if($ret["count"] > 0){
			$jan = $ret["data"][0];
			//このJANを使用済みにして時間登録
			$sqlu = "update `jan` set `status`=1,`regist`='".$cnf."' where `id`='".$jan["id"]."'";
			$retu = $inst->db_exec($sqlu);
			return $jan["jan"];
		}
		return;
	}
	function GetJan2($cnf){
		global $DB_URI;
		$inst = DBConnection::getConnection($DB_URI);
		//時間からJANコードを返す
		$sql = "select * from `jan` where `regist`='".$cnf."' limit 1";
		$ret = $inst->search_sql($sql);
		if($ret["count"] > 0){
			$jan = $ret["data"][0];
			return $jan["jan"];
		}
		return;
	}

	function create_pass(){
		$length = 9;

		$char = 'abcdefghijklmnopqrstuvwxyz1234567890-';
		 
		$charlen = mb_strlen($char);
		$result = "";
		 
		for($i=1;$i<=$length;$i++){
		  $index = mt_rand(0, $charlen - 1);
		  $result .= mb_substr($char, $index, 1);
		}

			return $result;
	}


	function PaymentPoast($datas){
				$POST_DATA = $datas;
				$url = "http://smartphonecase.shop/regist_api.php";
				$headers  		=  array( "Content-type: text/html" );
				$agent 			= "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36";
				
				$ch = curl_init($url);		
				curl_setopt($ch, CURLOPT_USERAGENT, $agent);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				$rc = curl_exec($ch);				
											
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_USERAGENT, $agent);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $POST_DATA);
				$rc = curl_exec($ch);
				return $rc;
	}

	exit;
?>