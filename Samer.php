
<?php
include 'Helper.php';
header("Content-Type: application/json; charset=UTF-8");
$Helper = new Helper();
$Helper->createDbConnection();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $Helper->insertNewEmployee($name, $email, $password);
}
?>