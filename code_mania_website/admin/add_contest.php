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
	$name = test_input($_POST["name"]);
	$stime = strtotime(test_input($_POST["stime"]));
	$stime = date('Y-m-d H:i:s', $stime);
	$etime = strtotime(test_input($_POST["etime"]));
	$etime = date('Y-m-d H:i:s', $etime);

   	if (empty($_POST["name"]) || empty($_POST["stime"]) || empty($_POST["etime"])) {
     		echo "Please fill required fileds";
   	} else {
		require_once("../includes/header.php");
$query = "INSERT INTO `contest`(`name`, `start_time`, `end_time`) VALUES (:name, '{$stime}', '{$etime}')";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
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
	Contest Name: <input type="text" name="name" value="<?php echo $name;?>">
	<br><br>
	Start Time(YYYY-MM-HH HH:MM:SS): <input type="text" name="stime" value="<?php echo $stime;?>">
	<br><br>
	End Time(YYYY-MM-HH HH:MM:SS): <input type="text" name="etime" value="<?php echo $etime;?>">
	<br><br>
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>
