<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

function getAllBacklinks ($url)
{
	global $googleanz;
	$ret = Array ();
	if (strlen ($url) > 7)
	{
		$urldata = parse_url ($url);
		$url = "http://" . $urldata["host"] . "/";
		$ip = gethostbyname  ($urldata["host"]);
		if ($ip == $urldata["host"])
		{
			$error .= "<br/>Ung&uuml;ltige Domain. Bitte http://www.meinedomain.de/ in das Feld URL eintragen.";
		} else {
			$yahooanz = getAnzInlinks ($url);
			$yahoolinks = getYahooLinks ($url);
			$googlelinks = getGoogleLinks ($url);
			$altavistalinks = getAltavistaLinks ($url);
			$msnlinks = getMSNLinks ($url);

			$alllinks = Array();
			// Yahoo Links verarbeiten
			for ($i = 0; $i < count ($yahoolinks); $i ++)
			{
				$link = $yahoolinks[$i];
				$alllinks[$link]['yahoo'] = 1;
			}
			// Google Links verarbeiten
			for ($i = 0; $i < count ($googlelinks); $i ++)
			{
				$link = $googlelinks[$i];
				$alllinks[$link]['google'] = 1;
			}
			// Altavista Links verarbeiten
			for ($i = 0; $i < count ($altavistalinks); $i ++)
			{
				$link = $altavistalinks[$i];
				$alllinks[$link]['altavista'] = 1;
			}
			// MSN Links verarbeiten
			for ($i = 0; $i < count ($msnlinks); $i ++)
			{
				$link = $msnlinks[$i];
				$alllinks[$link]['msn'] = 1;
			}

			$ips = Array();
			$hosts = Array();
			// Domains und IPs ermitteln
			foreach ($alllinks as $linkurl => $linkurlinfo)
			{
				$linkurlarray = parse_url ($linkurl);
				$host = $linkurlarray["host"];
				$ip = gethostbyname  ($host);
				if (!isset ($ips[$ip])) $ips[$ip] = 0;
				$ips[$ip] = $ips[$ip] + 1;	
				if (!isset ($hosts[$host])) $hosts[$host] = 0;
				$hosts[$host] = $hosts[$host] + 1;	
				$alllinks[$linkurl]['ip'] = $ip;
				$alllinks[$linkurl]['host'] = $host;
			}
			ksort ($alllinks);
				
			$info = "Insgesammt werden " . count($alllinks) . " unterschiedliche Links analysiert." . 
			        " Die Links werden von " . count($hosts) . " Domains und von " . count($ips) .
				" unterschiedlichen IP-Adressen geliefert.";
			$ret['links'] = $alllinks;
			$ret['url'] = $url;
			$ret['ip'] = $ip;
			$ret['info'] = $info;
			$ret['yahooanz'] = $yahooanz;
			$ret['googleanz'] = $googleanz;
			$ret['altavistaanz'] = count($altavistalinks);
			$ret['msnanz'] = count($msnlinks);
		}
	} else {
		$error .= "<br/>Bitte einen URL in der Form http://www.meinedomain.de/ in das Feld URL eintragen.";
	}
	if (isset ($error))
	{
		$ret['error'] = $error;
	}
	return $ret;
}

function is_valid_url($url) {
	$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]" .
		    "+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&" .
		    "%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
	if (eregi($urlregex, $url)) 
	{
		return true;
	} else {
		return false;
	} 
}

/*
 * convert a string to a 32-bit integer
 */
function StrToNum($Str, $Check, $Magic)
{
    $Int32Unit = 4294967296;  // 2^32

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic; 	
        //If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
        //  the result of converting to integer is undefined
        //  refer to http://www.php.net/manual/en/language.types.integer.php
        if ($Check >= $Int32Unit) {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
            //if the check less than -2^31
            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i}); 
    }
    return $Check;
}

/* 
 * Genearate a hash for a url
 */
function HashURL($String)
{
    $Check1 = StrToNum($String, 0x1505, 0x21);
    $Check2 = StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2; 	
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);	
	
    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
	
    return ($T1 | $T2);
}

/* 
 * genearate a checksum for the hash string
 */
function CheckHash($Hashnum)
{
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);
	
    for ($i = $length - 1;  $i >= 0;  $i --) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {              
            $Re += $Re;     
            $Re = (int)($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag ++;	
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2) ) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }

    return '7'.$CheckByte.$HashStr;
}

function getPageRank ($url, $proxys)
{
    $C = "http://www.google.com/search?client=navclient-auto&features=Rank:&q=info:";
    $data = $C.$url.'&ch='.CheckHash(HashURL($url));
    $snoopy = new Snoopy;
    $rank = " -";
    if(count ($proxys) > 0)
    {
    	foreach ($proxys as $proxy)
    	{
	    $snoopy->proxy_host = $proxy['host'];
	    $snoopy->proxy_port = $proxy['port'];
	    $snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)";
	    $snoopy->fetch($data);
	    if (strpos ($snoopy->results, "Rank_") === 0)
	    {
		$rarray = explode (":", $snoopy->results);
		$rank = trim($rarray[2]);
		return $rank;
	    }
    	}
    } else {
	    $snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)";
	    $snoopy->fetch($data);
	    if (strpos ($snoopy->results, "Rank_") === 0)
	    {
		$rarray = explode (":", $snoopy->results);
		$rank = trim($rarray[2]);
		return $rank;
	    }
    }
    return $rank;
}

function getUrl ()
{
	$url = $_GET['url'];
	$ereg = "((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)";
	if(!eregi($ereg,$url))
	{
		return false;
	}
	return $url;
}
?>
