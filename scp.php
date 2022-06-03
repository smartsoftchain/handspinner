<?php
$path = "./inc";
	require_once($path."/conf.php");
	require_once($path."/my_db.inc");
	require_once($path."/htmltemplate.inc");
	require_once($path."/errlog.inc");
	
	if($_REQUEST["mode"] == "kishu"){
		$cases = $_REQUEST["cases"];
		$str = "<option value=\"\">選択してください。</option>";
		foreach($case_list[$cases] as $key => $val){
			$str .= "<option value=\"".$key."\">".$color_list[$key]."</option>";
		}
		
		echo $str;
		exit;
	}elseif($_REQUEST["mode"] == "color"){
		
		$cases = $_REQUEST["cases"];
		$color = $_REQUEST["color"];
		$str = "";
		foreach($case_list[$cases][$color] as $key => $val){
			$str .= "<option value=\"".$key."\">".$val[0]."</option>";
		}
		
		echo $str;
		exit;
	}elseif($_REQUEST["mode"] == "price"){
		
		$color = $_REQUEST["color"];
		$value = $_REQUEST["value"];
		$str = "";
		$price = $case_list[$color][0][1];
		$str = (int)$price*(int)$value;
		//var_dump($_REQUEST);
		echo number_format($str);
		exit;
	}
	
	
	
	
	
	
	
	exit;
?>