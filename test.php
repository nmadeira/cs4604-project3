<?php
require('lib/db.php');
?>
<html>
<head>
	<title>PostgreSQL Test</title>
</head>
<body>
<?php
echo "<h1>PostgreSQL Test Page</h1>";

//$query = "SELECT relname FROM pg_stat_user_tables ORDER BY relname";
$query = "SELECT * FROM actin LIMIT 5";
$res = pg_query($query);
$out = array();
while ($row = pg_fetch_assoc($res)) {
	echo "- ";
	array_push($out, $row);
	foreach ($row as $key => $value) {
		echo "$key: $value, ";
	}
	echo "<br />";
}
$json = json_encode($out);
echo "$json";

pg_close($conn);
?>
</body>
</html>
