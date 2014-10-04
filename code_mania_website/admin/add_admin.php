<?php
	require_once("../includes/session.php");
	confirm_admin();
?>
<html>
<body>
<h1>Add Contest</h1><br>
<a href="admin.php">Back</a><br><br>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$uid = test_input($_POST["uid"]);

   	if (empty($_POST["uid"])) {
     		echo "Please fill required fileds";
   	} else {
		require_once("../includes/header.php");
$query = "INSERT INTO `admin`(`admin_id`) VALUES (:uid)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
		if($stmt->execute()) {
			echo "<h3>Contest Sucesfully Added</h3>";
		} else {
			print_r($stmt->errorInfo());
		}
     		//$qid = $desc = $setter = "";
   	}
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<br><br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	Username: <input type="text" name="uid" value="<?php echo $uid;?>">
	<br><br>
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>
