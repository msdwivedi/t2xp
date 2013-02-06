<?php require_once('../configure.php'); 
$input = $_GET["q"];
$data = array();
// query your DataBase here looking for a match to $input
$query = mysql_query("SELECT * FROM t2x_keywords WHERE keyword LIKE '%$input%' ");
while ($row = mysql_fetch_assoc($query)) {
$json = array();
$json['value'] = $row['keyword'];
$data[] = $json;
}
header("Content-type: application/json");
echo json_encode($data);
?>