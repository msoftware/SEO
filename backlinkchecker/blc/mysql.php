<?
/**
*
* @package Backlinkchecker
* @copyright (c) 2009 Michael Jentsch (http://www.webhosting-forum.net/)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$dblink = null;

function openDatabase ()
{
        global $dbhost, $dbname, $dbuser, $dbpass, $dblink;
        $dblink = mysql_connect($dbhost, $dbuser, $dbpass) or die('Could not connect to mysql server.' );
        mysql_select_db($dbname, $dblink) or die('Could not select database.');
}

function closeDatabase ()
{
        global $dblink;
        mysql_close($dblink);
}

function deletePagerankTable ()
{
        $sql = "DROP TABLE pagerank";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "error=$err\r\n";
}

function createPagerankTable ()
{
        $sql =  "CREATE TABLE pagerank ( " .
                "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY , " .
                "url VARCHAR( 255 ) NOT NULL , " .
                "pr VARCHAR( 2 ) NOT NULL , " .
                "timestamp BIGINT NOT NULL ) ";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "<font color='red'><b>Error: $err</b></font>";
}

function getPagerankData ()
{
        $rows = Array ();
        $sql = "SELECT * FROM pagerank ORDER BY pr DESC";
        $result = mysql_query($sql) or die('Error: ' . mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
                $rows[] = $row;
        }
        return $rows;
}

function getPagerankDataByURL ($url, $usetimeout = 1)
{
	global $pagerankcachetime;
	$timeout = time() - $pagerankcachetime;
	$url = mysql_escape_string ($url);
	if ($usetimeout == 1)
	{
        	$sql = "SELECT * FROM pagerank WHERE url='$url' AND timestamp > '$timeout'";
	} else {
        	$sql = "SELECT * FROM pagerank WHERE url='$url'";
	}
        $result = mysql_query($sql) or die('Error: ' . mysql_error());
        if ($row = mysql_fetch_assoc($result)) {
                return $row;
        }
        return false;
}

function savePagerank ($url, $pr)
{
        $url = mysql_escape_string ($url);
        $pr = mysql_escape_string ($pr);
	$time = time();

	$row = getPagerankDataByURL ($url, 0);
	if ($row == false)
	{
                // INSERT
                $sql =  "INSERT INTO pagerank (url, pr, timestamp) VALUES ('$url', '$pr', '$time')";
		$result = mysql_query($sql) or die('Error: ' . mysql_error());
	} else {
                // UPDATE
		$id = intval($row['id']);
		if ($id > 0)
		{
                	$sql =  "UPDATE pagerank SET pr='$pr', timestamp='$time' WHERE id='$id'";
			$result = mysql_query($sql) or die('Error: ' . mysql_error());
		} else {
			// TODO Error
		}
	}
}

function createBacklinkTable ()
{
        $sql =  "CREATE TABLE backlinks ( " .
                "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY , " .
                "url VARCHAR( 255 ) NOT NULL , " .
                "backlinks LONGTEXT NOT NULL , " .
                "timestamp BIGINT NOT NULL ) ";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "<font color='red'><b>Error: $err</b></font>";
}

function deleteBacklinkTable ()
{
        $sql = "DROP TABLE backlinks";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "error=$err\r\n";
}

function getBacklinkData ()
{
        $rows = Array ();
        $sql = "SELECT * FROM backlinks ORDER BY id DESC";
        $result = mysql_query($sql) or die('Error: ' . mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
                $rows[] = $row;
        }
        return $rows;
}

function getBacklinkDataByURL ($url, $usetimeout = 1)
{
	global $backlinkcachetime;
	$timeout = time() - $backlinkcachetime;
	$url = mysql_escape_string ($url);
	if ($usetimeout == 1)
        {
                $sql = "SELECT * FROM backlinks WHERE url='$url' AND timestamp > '$timeout'";
        } else {
        	$sql = "SELECT * FROM backlinks WHERE url='$url'";
        }

        $result = mysql_query($sql) or die('Error: ' . mysql_error());
        if ($row = mysql_fetch_assoc($result)) {
                return $row;
        }
        return false;
}

function saveBacklinks ($url, $backlinks)
{
        $url = mysql_escape_string ($url);
	$backlinks = serialize ($backlinks);
        $backlinks = mysql_escape_string ($backlinks);
	$time = time();

	$row = getBacklinkDataByURL ($url, 0);
	if ($row == false)
	{
                // INSERT
                $sql =  "INSERT INTO backlinks (url, backlinks, timestamp) VALUES ('$url', '$backlinks', '$time')";
		$result = mysql_query($sql) or die('Error: ' . mysql_error());
	} else {
                // UPDATE
		$id = intval($row['id']);
		if ($id > 0)
		{
                	$sql =  "UPDATE backlinks SET backlinks='$backlinks', timestamp='$time' WHERE id='$id'";
			$result = mysql_query($sql) or die('Error: ' . mysql_error());
		} else {
			// TODO Error
		}
	}
}

function deleteRequestTable ()
{
        $sql = "DROP TABLE pagerank";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "error=$err\r\n";
}

function createRequestTable ()
{
        $sql =  "CREATE TABLE request ( " .
                "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY , " .
                "url VARCHAR( 255 ) NOT NULL , " .
                "anz INT NOT NULL , " .
                "timestamp BIGINT NOT NULL ) ";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "<font color='red'><b>Error: $err</b></font>";
}

function getRequestData ()
{
        $rows = Array ();
        $sql = "SELECT * FROM request ORDER BY anz DESC";
        $result = mysql_query($sql) or die('Error: ' . mysql_error());
        while ($row = mysql_fetch_assoc($result)) {
                $rows[] = $row;
        }
        return $rows;
}

function getRequestDataByURL ($url)
{
	$url = mysql_escape_string ($url);
        $sql = "SELECT * FROM request WHERE url='$url'";
        $result = mysql_query($sql) or die('Error: ' . mysql_error());
        if ($row = mysql_fetch_assoc($result)) {
                return $row;
        }
        return false;
}

function saveRequest ($url)
{
        $url = mysql_escape_string ($url);
	$time = time();

	$row = getRequestDataByURL ($url);
	if ($row == false)
	{
                // INSERT
                $sql =  "INSERT INTO request (url, anz, timestamp) VALUES ('$url', '1', '$time')";
		$result = mysql_query($sql) or die('Error: ' . mysql_error());
	} else {
                // UPDATE
		$id = intval($row['id']);
		$anz = intval($row['anz']) + 1;
		if ($id > 0)
		{
                	$sql =  "UPDATE request SET anz='$anz', timestamp='$time' WHERE id='$id'";
			$result = mysql_query($sql) or die('Error: ' . mysql_error());
		} else {
			// TODO Error
		}
	}
}

function deleteProxyTable ()
{
        $sql = "DROP TABLE proxys";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "error=$err\r\n";
}


function createProxyTable ()
{
        $sql =  "CREATE TABLE proxys ( " .
                "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY , " .
                "host VARCHAR( 255 ) NOT NULL , " .
                "port VARCHAR( 10 ) NOT NULL , " .
                "state INT NOT NULL , " .
                "errorcount INT NOT NULL , " .
                "okcount INT NOT NULL , " .
                "delay INT NOT NULL , " .
                "quality INT NOT NULL , " .
                "timestamp BIGINT NOT NULL ) ";
        $result = mysql_query($sql);
        $err = mysql_error();
        if( $err != "" ) echo "<font color='red'><b>Error: $err</b></font>";
}

function getProxyData ($anz)
{
        $rows = Array ();
        $sql = "SELECT * FROM proxys WHERE state=1 ORDER BY quality DESC";
        $result = mysql_query($sql) or die('Error: ' . mysql_error());
	$pos = 0;
        while ($row = mysql_fetch_assoc($result)) 
	{ 
		if ($pos < $anz)
		{
			 $rows[] = $row; 
		}
		$pos++;
	}
        return $rows;
}

?>
