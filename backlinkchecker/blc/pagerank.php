<?php
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

error_reporting(E_ALL);
require "config.php";
require "mysql.php";
require "functions.php";
require "Snoopy-1.2.4/Snoopy.class.php";

$url = getUrl ();
// Session checken
if ($url == false)
{
	$pr = "x";
} else {
	openDatabase ();
	$pr = getPagerankDataByURL ($url);
	if ($pr === false || $_GET['cache'] == "false")
	{
		$proxys = getProxyData (12);
		$pr = getPageRank ($url, $proxys);
		savePagerank ($url, $pr);
	} else {
		$pr = $pr['pr'];
	}
	closeDatabase ();
}

// GIF Ausgabe
header ("Content-type: image/gif");
$num = $pr;
$width = 20;
$height = 15;
if (is_numeric($num))
{
	// Nur Zahlen
	if ($num < 10) $num = " " . $num;
} else {
	if (strlen ($num) == 1)
	{
		$num = " " . $num;
	}
}

$img = @imagecreatetruecolor ($width, $height) or die ("Kann kein Bild erzeugen");
$white = imagecolortransparent($img);
Imagefill($img, 0, 0, $white);
$gray = imagecolorallocate( $img, 128, 128, 128);
$black = imagecolorallocate( $img, 0,0,0);
ImageString ($img, 2, 5,1, $num, $black);
imagerectangle  ($img, 1, 1, $width-1, $height-1, $gray);
ImageGIF ($img);

?>
