<?php
include 'header.php';
$proxy = $_GET['prox'];
$login = $_GET['nn'];
switch($_GET['sys']){
	case 1:
		$url1 = 'http://www.mailinator.com/maildir.jsp?email='.strtolower($login).'&x='.mt_rand(1,54).'&y='.mt_rand(1,27);
		$por = '/msgid=(.*?)>/';
		break;
	case 2: 
		$url1 = 'http://www.mytrashmail.com/myTrashMail_inbox.aspx?email='.strtolower($login);
		$por = '/<b><a href=.MyTrashMail_message\.aspx\?(.*?).>/s';
		break;
	case 3:
		$url1 = 'http://www.tempinbox.com/cgi-bin/checkmail.pl?username='.strtolower($login).'&button=Check+Mail&terms=on';
		$por = '/<a href="\/cgi-bin\/viewmail\.pl\?id=(.*?)&kw/s';
		break;
	case 4:
		$url1 = 'http://'.strtolower($login).'.mailcatch.com/en/temporary-inbox';
		$por = '/show=(.*?)">/';
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

if (preg_match($por, $p,$akt) != TRUE){
	echo '<b>ERROR</b> Prawdopodobie mail aktywacyjny nie doszedł<br /><textarea rows="10" cols="150">'.$p.'<a href="tagakt.php?sys='.$_GET['sys'].'&prox='.$proxy.'&nn='.$login.'">Spróbuj ponownie</a><br />';
	die();
}
switch($_GET['sys']){
	case 1:
		$url2 = 'http://www.mailinator.com/showmail.jsp?email='.strtolower($login).'&msgid='.$akt[1];
		break;
	case 2: 
		$url2 = 'http://www.mytrashmail.com/MyTrashMail_message.aspx?'.$akt[1];
		break;
	case 3:
		$url2 = 'http://www.tempinbox.com/cgi-bin/viewmail.pl?id='.$akt[1].'&kw=TakeAGift.pl%20-%20potwierdzenie%20rejestracji';
		break;
	case 4:
		$url2 = 'http://mailcatch.com/en/temporary-inbox?box='.strtolower($login).'&show='.$akt[1];
		break;
	default: echo 'BŁĄD'; die(); break;
}
curl_setopt($hand, CURLOPT_URL, $url2);
$p=curl_exec($hand);
if (preg_match('/target=._blank.>(.*?).<\/a>/', $p,$wynik) != TRUE){
	echo '<b>ERROR</b> Nie znaleziono linku aktywacyjnego<br /><textarea rows="10" cols="150">'.$p.'</textarea><br><a href="tagakt.php?sys='.$_GET['sys'].'&prox='.$proxy.'&nn='.$login.'">Spróbuj ponownie</a><br />';
	die();
}
curl_setopt($hand, CURLOPT_URL, $wynik[1]);
$p=curl_exec($hand);
if (stripos($p,'Dziękujemy za potwierdzenie') !== false)
	echo '<b>Aktywowano ducha</b><br/><a href="tesco.php?sys='.$_GET['sys'].'">Jezcze raz</a><br />';
else{
	echo '<textarea rows="10" cols="150">'.$p.'</textarea><br><a href="tagakt.php?sys='.$_GET['sys'].'&prox='.$proxy.'&nn='.$login.'">Spróbuj ponownie</a><br />';
	die();
}
?>
