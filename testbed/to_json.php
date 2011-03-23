<?php
	$htmlencode=$_GET['htmlchars'];
	$arr=array();
	foreach($_GET as $str=>$val){
		if ($str<>'htmlchars')
			array_push($arr,array($str=>$val));
	}
	if ($htmlencode=='true'){
		echo htmlspecialchars(json_encode($arr));
	}else{
		echo json_encode($arr);
	}
?>
