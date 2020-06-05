<?php
error_reporting(-1);
ini_set('display_errors', 'On');
if ($_POST) { // e��� �e�e�a� �a���� POST
	$name = htmlspecialchars($_POST["name"]); // ���e� �a���e � �e�e�e���e � ���a����e� ��e�����o��
	$email = htmlspecialchars($_POST["mail"]);
	$number = htmlspecialchars($_POST["number"]);
	$message = htmlspecialchars($_POST["message"]);
	$subject=htmlspecialchars($_POST["trip"]);
	$json = array(); // �o��o�o��� �a���� o��e�a
	if (!$name or !$email or !$number) { // e��� �o�� o��o �o�e o�a�a�o�� ������
		$json['error'] = 'Required field/s are/is empty.'; // ���e� o����� � �a����
		echo json_encode($json); // ���o��� �a���� o��e�a 
		die(); // ����ae�
	}
	if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email)) { // ��o�e��� email �a �a����o���
		$json['error'] = 'Wrong e-mail format!'; // ���e� o����� � �a����
		echo json_encode($json); // ���o��� �a���� o��e�a
		die(); // ����ae�
	}

	function mime_header_encode($str, $data_charset, $send_charset) { // ������� ��eo��a�o�a��� �a�o�o��o� � �e���� �o���o��� 
		if($data_charset != $send_charset)
		$str=iconv($data_charset,$send_charset.'//IGNORE',$str);
		return ('=?'.$send_charset.'?B?'.base64_encode($str).'?=');
	}
	/* ���e� ��a�� ��� o���a��� �����a � ����o� �o���o��e */
	class TEmail {
	public $from_email;
	public $from_name;
	public $to_email;
	public $to_name;
	public $subject;
	public $data_charset='UTF-8';
	public $send_charset='windows-1251';
	public $body='';
	public $type='text/plain';

	function send(){
		$dc=$this->data_charset;
		$sc=$this->send_charset;
		$enc_to=mime_header_encode($this->to_name,$dc,$sc).' <'.$this->to_email.'>';
		$enc_subject=mime_header_encode($this->subject,$dc,$sc);
		$enc_from=mime_header_encode($this->from_name,$dc,$sc).' <'.$this->from_email.'>';
		$enc_body=$dc==$sc?$this->body:iconv($dc,$sc.'//IGNORE',$this->body);
		$headers='';
		$headers.="Mime-Version: 1.0\r\n";
		$headers.="Content-type: ".$this->type."; charset=".$sc."\r\n";
		$headers.="From: ".$enc_from."\r\n";
		return mail($enc_to,$enc_subject,$enc_body,$headers);
	}

	}

	$emailgo= new TEmail; // �����a������e� ���e� ��a�� o���a���
	$emailgo->from_email= $email; // o� �o�o
	$emailgo->from_name= $name;
	$emailgo->to_email= 'samigullinroma@gmail.com'; // �o��
	$emailgo->to_name= 'Roman Samiguzlo';
	$emailgo->subject= $subject; // �e�a
	$bodymail = "Hi, my name is ";
	$bodymail .= $name;
	$bodymail .= ". ";
	$bodymail .= " Telephone: ";
	$bodymail .= $number;
	$bodymail .= " Message: ";
	$bodymail .= $message;
	$emailgo->body=$bodymail ; // �oo��e��e
	$emailgo->send(); // o���a���e�

	$json['error'] = 0; // o���o� �e ���o

	echo json_encode($json); // ���o��� �a���� o��e�a
} else { // e��� �a���� POST �e ��� �e�e�a�
	echo 'GET LOST!'; // �����ae�
}
?>