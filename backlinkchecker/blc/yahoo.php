<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once "Snoopy-1.2.4/Snoopy.class.php";

function getYahooLinks ($term)
{
	$snoopy = new Snoopy;
	$url = "http://siteexplorer.search.yahoo.com/export" .
							"?p=" . urlencode  ($term) .
							"&bwm=i" .
							"&bwmf=s" .
							"&bwmo=d";
        $snoopy->fetch($url);
       	return decodeYahooString ($snoopy->results);
}

function decodeYahooString ($data)
{
	$result = Array ();
	$entries = explode ("\n", $data);
	array_shift ($entries);
	array_shift ($entries);
	for ($i = 0; $i < count ($entries); $i ++)
	{
		$entry = $entries[$i];
		$data = explode ("	", $entry);
		if (count ($data) == 4)
		{
			$result[] = $data[1];
		}
	}
	return ($result);
}

function getAnzInlinks ($term)
{
	$snoopy = new Snoopy;
	$url = "http://siteexplorer.search.yahoo.com/search" .
							"?p=" . urlencode  ($term) .
							"&bwm=i" .
							"&bwmo=d" .
							"&bwmf=s";
        $snoopy->fetch($url);
	$data = explode ("Inlinks (", $snoopy->results);
	$data = $data[1];
	$data = explode (")", $data);
	$data = $data[0];
	$data = str_replace(",", "", $data);
	
	return $data;
}

?>
