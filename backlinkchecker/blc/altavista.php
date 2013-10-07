<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once "Snoopy-1.2.4/Snoopy.class.php";

function getAltavistaLinks ($server)
{
	$all = Array ();
	$maxi = 1000;
	$serverarray = parse_url ($server);
	for ($i = 0; $i < $maxi; $i += 10) 
	{
		// Bei large immer 10 Eintraege Pro Seite
		// Max Start ist 1000 
		$entries = getAltavistaData ("linkdomain:" . $server, $i);
		for ($j = 0; $j < count ($entries); $j ++)
		{
			$urlarray = parse_url ($entries[$j]);
			if (strpos ($urlarray['host'], $serverarray['host']) === FALSE && strpos ($serverarray['host'], $urlarray['host']) === FALSE)
			{
				$all[] = $entries[$j];
			}
		}
		$myanz = count ($all);
		$all = removeDoubleAltavista ($all);
		$mynewanz = count ($all);
		if ($mynewanz < $myanz)
		{
			// Sorry, aber es geht nicht anders :-)
			return $all;
		}
		if (count ($entries) < 10)
		{
			$i = $maxi + 1;
		}
	}
	return $all;
}

function removeDoubleAltavista ($all)
{
	foreach ($all as $entry)
	{
		$test[$entry] = $entry;
	}
	foreach ($test as $entry)
	{
		$new[] = $entry;
	}
	
	return $new;
}

function getAltavistaData ($term, $pos)
{
	$snoopy = new Snoopy;
	$url = "http://www.altavista.com/web/results" .
					"?itag=ody" .
					"&q=" . urlencode ($term) .
					"&kgs=1" .
					"&stq=" . $pos . 
					"&kls=0";
        $snoopy->fetch($url);
	$entries = decodeAltavistaString ($snoopy->results);
	return $entries;
}

function decodeAltavistaString ($data)
{
	$result = Array ();
	$entries = explode ("<br class='lb'><a class='res'", $data);
	array_shift ($entries);
	for ($i = 0; $i < count ($entries); $i ++)
	{
		$entry = $entries[$i];
		$entry = explode (">", $entry);
		$entry = $entry[0];
		$entry = explode ("'", $entry);
		$entry = $entry[1];
		$result[] = $entry;
	}
	return ($result);
}

?>
