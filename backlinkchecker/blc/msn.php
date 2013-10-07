<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once "Snoopy-1.2.4/Snoopy.class.php";

function getMSNLinks ($server)
{
	$all = Array ();
	$serverarray = parse_url ($server);
	for ($i = 1; $i < 1000; $i += 10) 
	{
		$entries = getMSNData ("link:" . $server . " -site:" . $server, $i);
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

function getMSNData ($term, $pos)
{
	$snoopy = new Snoopy;
	$url = "http://search.live.com/results.aspx" .
					"?q=" . urlencode ($term) .
					"&first=" . $pos .
					"&FORM=PERE4";

        $snoopy->fetch($url);
       	return decodeMSNString ($snoopy->results);
}

function decodeMSNString ($data)
{
	$result = Array ();
	$entries = explode ("<li><h3><a href=\"", $data);
	array_shift ($entries);
	
	for ($i = 0; $i < count ($entries); $i ++)
	{
		$entry = $entries[$i];
		$entry = explode ("\" ", $entry);
		$entry = $entry[0];
		$result[] = $entry;
	}
	return ($result);
}

?>
