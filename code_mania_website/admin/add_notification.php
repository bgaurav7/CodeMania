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
	$noti = test_input($_POST["noti"]);

   	if (empty($_POST["noti"])) {
     		echo "Please fill required fileds";
   	} else {
		require_once("../includes/header.php");
$query = "INSERT INTO `notice`(`notice`) VALUES (:noti)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':noti', $noti, PDO::PARAM_STR);
		if($stmt->execute()) {
			echo "<h3>Contest Sucesfully Added</h3>";
		} else {
			print_r($stmt->errorInfo());
		}
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
	Notification: <textarea name="noti" rows="5" cols="40"><?php echo $noti;?></textarea>
	<br><br>
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>
