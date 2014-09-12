<?php
	include("sendConfig.php");
	$_POST['type'] = (empty($_POST['type']) ? $_GET['a'] : $_POST['type']);

	if(!preg_match('@('.join('|',array_keys($formTypes)).')@',$_POST['type']))
	{
		die('Undefined Form Type');
	}
	
	if(empty($_POST['send']))
	{
		$temp 			= $_POST;			unset($_POST);
		$_POST['type']	= $temp['type']; 	unset($temp['type']);
		$_POST['send'] 	= $temp;			unset($temp);
	}
	
	array_filter($_POST['send'],"clean");
	
	//Info Send
	require_once('classes/class.phpmailer.php');
	$mail             	= new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  	= true;
	$mail->SMTPAuth   	= true;
	$mail->Host       	= 'smtp.1and1.es'; // ssl://smtp.gmail.com
	$mail->Port       	= 587; // 465
	$mail->Username   	= 'info@aritzondarra.com';
	$mail->Password   	= 'fasf784saTs6';
	$mail->Subject    	= 'New '.$formTypes[$_POST['type']]. ' Message from aritzondarra.com';
	$mail->CharSet		= 'utf-8';
	$mail->WordWrap 	= 50;
	
	$mail->SetFrom('info@aritzondarra.com', 'aritzondarra.com');

	$mail->AddAddress('aritz.ondarra@gmail.com');
	
	extract($_POST['send']);

	ob_start();

	include(MAIL_TEMPLATE_PATH.$_POST['type'].'.php');
	
	$mail->MsgHTML(ob_get_contents());print_r($mail);

	$resp = array();
  $resp['send'] = $mail->Send();
  $resp['error'] = $mail->ErrorInfo;

	ob_end_clean();
	unset($mail);
	header('content-type:text/html; charset=utf8');
	exit(json_encode($resp));
?>