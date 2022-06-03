<?php
/*
印刷サイズ
394.016×196.441
382.678×181.417



*/

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

$site_url = "http://myhandspinner.net/";
		
		$cnf = $_REQUEST["cnf"];
		$chk = $_REQUEST["chk"];
		$x = (isset($_REQUEST['x'])) ? $_REQUEST['x'] : 0;
		$y = (isset($_REQUEST['y'])) ? $_REQUEST['y'] : 0;
		$xt = (isset($_REQUEST['xt'])) ? $_REQUEST['xt'] : 0;
		$yt = (isset($_REQUEST['yt'])) ? $_REQUEST['yt'] : 0;
		$x = $x -164;
		//$x = $_REQUEST["x"]-164;
		//$y = $_REQUEST["y"];
		$h = $_REQUEST["h"];
		$w = $_REQUEST["w"];
		$rote = $_REQUEST["rote"];
		$files = $_REQUEST["files"];
		//$chk = "on";
		$value = $_REQUEST["value"];
		$price = (isset($_REQUEST['price'])) ? $_REQUEST['price'] : 0;
		$price = str_replace(",","",$price);
		$price = (int)$price/$value;
		$status = (isset($_REQUEST['status'])) ? $_REQUEST['status'] : 3;
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
		$canvas2 = $_REQUEST["img2"];
		
			$name = $cnf;
			$name2 = $cnf."_result";
			$zipname = $cnf.".zip";
			$jan_str = $cnf;

			//@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log2.txt", $canvas."\n", FILE_APPEND);
		//ヘッダに「data:image/png;base64,」が付いているので、それは外す
		$canvas = preg_replace("/data:[^,]+,/i","",$canvas);
		$canvas = base64_decode($canvas);
		$image = imagecreatefromstring($canvas);
		imagesavealpha($image, TRUE);
		imagepng($image ,'./convert/'.$name.'.png');
		
		$canvas2 = preg_replace("/data:[^,]+,/i","",$canvas2);
		$canvas2 = base64_decode($canvas2);
		$image2 = imagecreatefromstring($canvas2);
		imagesavealpha($image2, TRUE);
		imagepng($image2 ,'./convert/bg.png');
		
		//画像合成
		if(file_exists("./convert/".$name.".png")){
			//元ファイルをコピー
			exec("cp ./upload/".$files." ./convert/".$files);
			list($imgwidth3, $imgheight3) = getimagesize("./convert/".$name.".png");
			if($imgwidth3 > 625){
				$scp = "/usr/local/bin/convert ./convert/".$name.".png -resize 625x625\! ./convert/".$name.".png";
				exec($scp,$res);
			}
			
			
			//元ファイルを625の台紙に合成
			/*
			$scp = "/usr/local/bin/convert -size 625x625 xc:white ./convert/".$cnf."_basic.png";
			exec($scp,$res);
			@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);
			*/
			$scp = "/usr/local/bin/composite -gravity center -compose dst-in ./case/bg.png ./convert/".$name.".png -matte ./convert/".$name2.".png";
			exec($scp,$res);
			@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);
			
			//ケース画像をトリミング
			
			$scp = "/usr/local/bin/convert ./convert/".$name.".png -trim ./convert/".$name.".png";
			exec($scp);
			@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);
			$scp = "/usr/local/bin/convert ./convert/".$name2.".png -trim ./convert/".$name2.".png";
			exec($scp);
			
			@file_put_contents(dirname(__FILE__)."/log/".date("Ymd")."_log.txt", $scp."\n", FILE_APPEND);

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
			    $zip->addFile($files);
			    if($chk == "on"){
			    	//$zip->addFile($jan."_jancode.png");
			    }
			    // ZIPファイルをクローズ
			    $zip->close();
			}
			$waku_url = $site_url."convert/".$name.'.png';
			$gosei_url = $site_url."convert/".$name2.'.png';
			$jan_url = $cnf;
			
					$datas = array(
						//"name"=>$product_name,
						"name"=>"オリジナルハンドスピナー_".$cnf,
						"product_code"=>$cnf,
						"price02"=>$price,
						"quantity"=>$value,
						"status"=>$status,
						"main_image"=>$waku_url,
						"sub_image1"=>$gosei_url,
						"sub_image2"=>"",
						"sub_image3"=>"",
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
			//$ret = $inst->search_sql($sql);
			
			echo $result."■".$zipname;



			
		}
		exit;
	}else if($_REQUEST["mode"] == "img2"){
		$canvas2 = $_REQUEST["img2"];
		$cnf = $_REQUEST["cnf"];
		$canvas2 = preg_replace("/data:[^,]+,/i","",$canvas2);
		$canvas2 = base64_decode($canvas2);
		$image2 = imagecreatefromstring($canvas2);
		imagesavealpha($image2, TRUE);
		imagepng($image2 ,'./convert/'.$cnf.'_bg.png');
		echo "";
		exit;
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