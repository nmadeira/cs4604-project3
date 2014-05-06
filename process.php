<?php
require('lib/db.php');

/**
* performs a query and returns the array of results
*/
function db_query_array($query) {
	$res = pg_query($query);
	$out = array();
	while ($row = pg_fetch_assoc($res)) {
		array_push($out, $row);
	}
	return $out;
}

/**
* returns size of a table
*/
function table_size($table) {
	$query = "SELECT COUNT(*) FROM $table";
	$res = pg_query($query);
	$size = pg_fetch_assoc($res);
	return $size['count'];
}

/**
* performs a query and returns size of the results
*/
function query_size($query) {
	$res = pg_query($query);
	$size = pg_num_rows($res);
	return $size;
}

/**
* redirecting user to homepage in case of an invalid request
*/
function invalid_request() {
	echo "Invalid Request!";
	echo "<script type='text/javascript'>setTimeout('window.location = \"index.php\"', 5000);</script>";
	//header( 'Location: index.php' ) ;
	exit();
}

// identify type of the requested operation
$o = $_REQUEST['o'];

switch ($o) {
	case 's': // select operations
		$t = $_REQUEST['t']; // table name
		$l = 20; // limit on number of returned rows
		if(isset($_REQUEST['l'])) {
			$l = $_REQUEST['l'];
		}
		$p = 1; // page number (offset)
		if(isset($_REQUEST['p'])) {
			$p = $_REQUEST['p'];
		}
		$so = "";
		if(isset($_REQUEST['so']) && $_REQUEST['so'] != "") {
			$so = " ORDER BY ".$_REQUEST['so'];
			if(isset($_REQUEST['or']) && $_REQUEST['or'] != "") {
				$so .= " DESC";
			}
		}
		$offset = ($p - 1) * $l; // calculate sql rows offset
		$query = "SELECT * FROM $t$so LIMIT $l OFFSET $offset";
		$data['rows'] = db_query_array($query);
		$data['table'] = $t;
		$data['page'] = $p;
		$data['limit'] = $l;
		$data['size'] = table_size($t);
		if(isset($_REQUEST['so'])) {
			$data['sort'] = $_REQUEST['so'];
			if(isset($_REQUEST['or'])) {
				$data['order'] = $_REQUEST['or'];
			}
		}
		echo json_encode($data);
		break;

	case 'i': // insert operations
		$table = "";
		$fields = "(";
		$values = "(";
		$count = 0;
		foreach ($_REQUEST as $key => $value) {
			$count++;
			if($key == "table") {
				$table = $value;
				continue;
			}
			if($key == "o") continue;
			if($count > 1) {
				$fields .= ",";
				$values .= ",";
			}
			$fields .= $key;
			$values .= "'".$value."'";
		}
		$fields .= ")";
		$values .= ")";
		$query = "INSERT INTO $table $fields VALUES $values";
		$res = pg_query($query) or die('error');
		echo "";
		break;

	case 'd': // delete operations
		$table = "";
		$fields = "";
		$count = 0;
		foreach ($_REQUEST as $key => $value) {
			$count++;
			if($key == "table") {
				$table = $value;
				continue;
			}
			if($key == "o") continue;
			if($count > 1) {
				$fields .= "AND ";
			}
			$fields .= "$key = '$value' ";
		}
		$query = "DELETE FROM $table WHERE $fields";
		$res = pg_query($query) or die('error');
		echo "";
		break;

	case 'u': // update operations
		# code...
		break;

	case 'q': // ad-hoc queries
		$q = $_REQUEST['q']; // table name
		$l = 20; // limit on number of returned rows
		if(isset($_REQUEST['l'])) {
			$l = $_REQUEST['l'];
		}
		$p = 1; // page number (offset)
		if(isset($_REQUEST['p'])) {
			$p = $_REQUEST['p'];
		}
		$so = "";
		if(isset($_REQUEST['so']) && $_REQUEST['so'] != "") {
			$so = " ORDER BY ".$_REQUEST['so'];
			if(isset($_REQUEST['or']) && $_REQUEST['or'] != "") {
				$so .= " DESC";
			}
		}
		$offset = ($p - 1) * $l; // calculate sql rows offset
		$query = "$q$so LIMIT $l OFFSET $offset";
		$query = $q;
		if(!strpos(strtolower($q), "limit")) {
			$query .= " LIMIT $l OFFSET $offset";
		}
		// echo $query;
		$data['rows'] = db_query_array($query);
		$data['query'] = $q;
		$data['page'] = $p;
		$data['limit'] = $l;
		if(strpos(strtolower($q), "limit")) {
			$data['size'] = $l;
		} else {
			$data['size'] = query_size($q);
		}
		if(isset($_REQUEST['so'])) {
			$data['sort'] = $_REQUEST['so'];
			if(isset($_REQUEST['or'])) {
				$data['order'] = $_REQUEST['or'];
			}
		}
		echo json_encode($data);
		break;
	
	default: // all other (illegal) operations
		invalid_request();
}

pg_close($conn);
?>
