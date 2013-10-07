<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

error_reporting (0);
include "config.php";
include "functions.php";
include "google.php";
include "altavista.php";
include "yahoo.php";
include "msn.php";
require "mysql.php";


$url = $_REQUEST['url'];
$cache = $_REQUEST['cache'];
if (is_valid_url($url))
{
	openDatabase ();
	saveRequest($url);
	if ($cache == "false")
	{
		// Keinen Cache verwenden
	} else {
        	$bldata = getBacklinkDataByURL($url);
	}
	// TODO: Timestamp checken
	if ($bldata == false)
        {
		$data = getAllBacklinks ($url);
		$data ['url'] = $url;
		$text = $data['info'];
		$proxys = getProxyData (4);
		$pr = getPageRank ($url, $proxys);
		$data ['pr'] = $pr;
		$data ['text'] = $text;
                saveBacklinks ($url, $data);
        } else {
		$data = unserialize ($bldata['backlinks']);
	}

	$links = $data['links'];
        $ip = $data ['ip'];
        $pr = $data ['pr'];
        $text = $data ['text'];
        $msn = intval($data['msnanz']);
        $yahoo = intval($data['yahooanz']);
        $google = intval($data['googleanz']);
        $altavista = intval($data['altavistaanz']);
	closeDatabase();

?>
{"backlinks": [
<?
	$i = 0;
	foreach ($links as $linkurl => $link)
	{
		$linkurl = str_replace  ("\"" ,"&#034;", $linkurl);
		$googlelink = 0;
		if (isset ($link['google'])) $googlelink = 1;
		$yahoolink = 0;
		if (isset ($link['yahoo'])) $yahoolink = 1;
		$altavistalink = 0;
		if (isset ($link['altavista'])) $altavistalink = 1;
		$msnlink = 0;
		if (isset ($link['msn'])) $msnlink = 1;
		if ($i + 1 < count ($links))
		{
			$space = ",";
		} else {
			$space = "";
		}
?>
   {"url":"<?=$linkurl?>","ip":"<?=$link['ip']?>","google":"<?=$googlelink?>","yahoo":"<?=$yahoolink?>","altavista":"<?=$altavistalink?>","msn":"<?=$msnlink?>"}<?=$space?>
<?
		$i ++;
	}
?>
], "overview": [
   {"name":"MSN Links","value":"<?=$msn?>"},
   {"name":"Altavista Links","value":"<?=$altavista?>"},
   {"name":"Yahoo Links","value":"<?=$yahoo?>"},
   {"name":"Google Links","value":"<?=$google?>"},
   {"name":"Pagerank","value":"<?=$pr?>"},
   {"name":"IP Adresse","value":"<?=$ip?>"},
   {"name":"URL","value":"<?=$url?>"}
], "text": [
   {"text":"<?=$text?>"}
]
}
<?
} else {
	// Ungueltiger URL
?>
{"backlinks": [
], "overview": [
], "text": [
   {"text":"<font color='red'>Ung&uuml;ltiger URL. Bitte einen URL in der Form http://www.webhosting-forum.net/ angeben.</font>"}
]
}
<?
}
?>
