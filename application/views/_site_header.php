<!DOCTYPE html>
<html >
<head>
	<meta charset="utf-8">
	<title><?php 
		if(isset($pageTitle)){ 
			echo $pageTitle ; //透過變數設定
		} else{ 
			echo "發文系統" ; //預設標題
		} 
	?></title>
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap.min.css")?>">
    <link rel="stylesheet" href="<?=base_url("/css/bootstrap-responsive.min.css")?>">
</head>
<body>