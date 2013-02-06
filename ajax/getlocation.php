<?php require_once('../configure.php'); 
$input = $_GET["q"];
$data = array();
// query your DataBase here looking for a match to $input
$query = mysql_query("SELECT * FROM t2x_cities WHERE name LIKE '%$input%' ");
while ($row = mysql_fetch_assoc($query)) {
$json = array();
$json['value'] = $row['name'];
$data[] = $json;
}
header("Content-type: application/json");
echo json_encode($data);
?>