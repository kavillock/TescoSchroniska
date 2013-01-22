<?php
function isu($a){return iconv('ISO-8859-2','UTF-8',$a);}
function zamiana($tekst){ $wynik = strtr($tekst,'ĄĆĘŁŃÓŚŻŹąćęłńóśżź','ACELNOSZZacelnoszz'); return $wynik; }

header('Content-type: text/html; charset=utf-8'); 
$naglowki=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
				'Accept-Language: pl,en-us;q=0.7,en;q=0.3',
				'Accept-Charset: ISO-8859-2,utf-8;q=0.7,*;q=0.7');
$encode = 'Accept-Encoding: gzip,deflate';
$usera = 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 5.1)';

$ciastko = dirname(__FILE__).'../tmp/tag_'.md5(time()).'.tmp';

$proxylist = file(dirname(__FILE__).'/proxy.txt');
?>
