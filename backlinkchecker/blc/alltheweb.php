<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once "Snoopy-1.2.4/Snoopy.class.php";

function getAllthewebLinks ($server)
{
	$all = Array ();
	$serverarray = parse_url ($server);
	for ($i = 0; $i < 1000; $i += 10) 
	{
		$entries = getAllthewebData ("link.all:" . $server, $i);
		for ($j = 0; $j < count ($entries); $j ++)
		{
			$urlarray = parse_url ($entries[$j]);
			if (strpos ($urlarray['host'], $serverarray['host']) === FALSE && strpos ($serverarray['host'], $urlarray['host']) === FALSE)
			{
				$all[] = $entries[$j];
			}
		}
		if (count ($entries) < 1) $i = 10000;
	}
	return $all;
}

function getAllthewebData ($term, $pos)
{
	$snoopy = new Snoopy;
	$url = "http://www.alltheweb.com/search" .
					"?cat=web" .
					"&cs=iso88591" .
					"&rys=0" .
					"&itag=crv" .
					"&_sb_lang=pref" . 
					"&ocjp=1" .
					"&q=" . urlencode ($term) .
					"&o=" . $pos;

        $snoopy->fetch($url);
       	return decodeAllthewebString ($snoopy->results);
}

function decodeAllthewebString ($data)
{
	$result = Array ();
	$entries = explode ("Web Results", $data);
	$data = $entries[1];
	$entries = explode ("<span class=\"resURL\">", $data);
	array_shift ($entries);
	
	for ($i = 0; $i < count ($entries); $i ++)
	{
		$entry = $entries[$i];
		$entry = explode ("</span>", $entry);
		$entry = $entry[0];
		$entry = trim ($entry);
		$result[] = $entry;
	}
	return ($result);
}

?>
