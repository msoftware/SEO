<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once "Snoopy-1.2.4/Snoopy.class.php";

$googleanz = 0;

function getGoogleLinks ($server)
{
	$all = Array ();
	$serverarray = parse_url ($server);
	for ($i = 0; $i < 57; $i += 8) 
	{
		// Bei large immer 8 Eintraege Pro Seite
		// Max Start ist 56
		$entries = getGoogleData ("link:" . $server, $i);
		for ($j = 0; $j < count ($entries); $j ++)
		{
			$urlarray = parse_url ($entries[$j]);
			if (strpos ($urlarray['host'], $serverarray['host']) === FALSE && strpos ($serverarray['host'], $urlarray['host']) === FALSE)
			{
				$all[] = $entries[$j];
			}
		}
	}
	return $all;
}

function getGoogleData ($term, $pos)
{
	$snoopy = new Snoopy;
	$url = "http://www.google.de/uds/GwebSearch" .
				"?callback=google.search.WebSearch.RawCompletion" .
				"&context=0" .
				"&lstkp=0" .
				"&start=" . $pos .
				"&rsz=large" .
				"&hl=de" .
				"&gss=.com" .
				"&sig=6520c5e1eff94d2f0b58de01c9739f48" .
				"&key=notsupplied" .
				"&v=1.0" .
				"&q=" . urlencode  ($term);
        $snoopy->fetch($url);
       	return decodeGoogleString ($snoopy->results);
}

function decodeGoogleString ($data)
{
	global $googleanz;
	$result = Array ();
	$anzinfo = explode ("estimatedResultCount", $data);
	$anzinfo = explode (",",  $anzinfo[1]);
	$anzinfo = preg_replace('/[^0-9]/','',$anzinfo[0]);
	if ($googleanz == 0)
	{
		$googleanz = intval($anzinfo);
	}
	$entries = explode ("GsearchResultClass", $data);
	array_shift ($entries);
	for ($i = 0; $i < count ($entries); $i ++)
	{
		$entry = $entries[$i];
		$data = explode (":", $entry);
		for ($j = 0; $j < count ($data); $j ++)
		{
			if (strpos ($data[$j], "visibleUrl") > 0)
			{
				$url = explode ("\",\"", $data[$j]);
				$result[] = "http:" . $url[0];
			}
		}
	}
	return ($result);
}

?>
