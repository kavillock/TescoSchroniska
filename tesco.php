<?php
include 'header.php';

$proxyile = mt_rand(0,108);
$proxy = substr($proxylist[$proxyile],0,-1);
$url1 = 'http://www.tesco.pl/schroniska/index.php';
//IMIE
$imielist = file(dirname(__FILE__).'/imiona.txt');
$imieile = mt_rand(0,1645);
$imie = substr($imielist[$imieile],0,-1);
//NAZWISKO
$nazwiskolist = file(dirname(__FILE__).'/nazwiska.txt');
$nazwiskoile = mt_rand(0,600);
$nazwisko = substr($nazwiskolist[$nazwiskoile],0,-1);
//DATA URODZENIA
$dzien = mt_rand(1,27);
$miesiac = mt_rand(1,12);
$rok = mt_rand(1950,1997);
//LOGIN
switch(mt_rand(1,6)){
	case 1: $login = substr($imie,0,6).substr(strtolower($nazwisko),0,4); break;
	case 2: $login = substr($imie,0,6).$rok; break;
	case 3: $login = substr($imie,0,6).$dzien.$miesiac; break;
	case 4: $login = substr($nazwisko,0,6).substr(strtolower($imie),0,4); break;
	case 5: $login = substr($nazwisko,0,6).$rok; break;
	case 6: $login = substr($nazwisko,0,6).$dzien.$miesiac; break;
}
$login = zamiana($login);
//MAIL
switch($_GET['sys']){
	case 1:
		$mail = '@mailinator.com';
		$mail2 = 'http://www.mailinator.com/maildir.jsp?email='.strtolower($login).'&x='.mt_rand(1,54).'&y='.mt_rand(1,27);
		break;
	case 2: 
		$mail = '@mailmetrash.com';
		$mail2 = 'http://www.mytrashmail.com/myTrashMail_inbox.aspx?email='.strtolower($login);
		break;
	case 3:
		$mail = '@tempinbox.com';
		$mail2 = 'http://www.tempinbox.com/cgi-bin/checkmail.pl?username='.strtolower($login).'&button=Check+Mail&terms=on';
		break;
	case 4:
		$mail = '@mailcatch.com';
		$mail2 = 'http://'.strtolower($login).'.mailcatch.com/en/temporary-inbox';
		break;
	default: echo 'BŁĄD'; die(); break;
}

$hand = curl_init();
curl_setopt($hand, CURLOPT_URL, $url1);
curl_setopt($hand, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($hand, CURLOPT_USERAGENT, $usera);
curl_setopt($hand, CURLOPT_COOKIESESSION, true);
curl_setopt($hand, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($hand, CURLOPT_HTTPHEADER, $naglowki);
curl_setopt($hand, CURLOPT_ENCODING, $encode);
curl_setopt($hand, CURLOPT_COOKIEFILE, $ciastko);
curl_setopt($hand, CURLOPT_COOKIEJAR, $ciastko);
curl_setopt($hand, CURLPROXY_HTTP, $proxy);
curl_setopt($hand, CURLOPT_AUTOREFERER, true);
$p=curl_exec($hand);

//REJESTRACJA
curl_setopt($hand, CURLOPT_URL, $url1);
curl_setopt($hand, CURLOPT_POST, 1);
curl_setopt($hand, CURLOPT_POSTFIELDS, array(
	'schronisko' => '785', 
	'zgoda2' => '1',
	'email' => strtolower($login).$mail,
	));
$p=curl_exec($hand);
if (stripos($p,'potwierdzeniu w wiadomo') !== false){
	echo '<b>Zarejestrowano Schronisko:</b><br/><textarea rows="10" cols="150">'.
	'Mail: '.$mail2."\n".
	'</textarea><br>';
}else{
	echo '<b>Error:</b><br/><textarea rows="10" cols="150">'.$p.'</textarea><br><a href="tesco.php?sys='.$_GET['sys'].'">Spróbuj ponownie</a><br />'.$_GET['id'];
	die();
}
echo '<a href="tescoakt.php?sys='.$_GET['sys'].'&prox='.$proxy.'&nn='.$login.'">Aktywuj ducha</a><br />';

?>
