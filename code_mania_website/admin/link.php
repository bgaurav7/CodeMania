<?php
	require_once("../includes/session.php");
	confirm_admin();
	require_once("../includes/header.php");
?>
<html>
<body>
<h1>Link Que & Contest</h1><br>
<a href="admin.php">Back</a><br><br><br>

<h3>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(empty($_POST['contest'])) {
		echo "Please select a contest";
	} else {
		$qid = $_POST['submit'];
		$cid = $_POST['contest'];
		$query = "UPDATE `question` SET `contest_id`=:cid WHERE `qid` = :qid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
		$stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
		if($stmt->execute()) {
			echo "<h3>Sucesfully Linked Que:".$qid." to Contest:".$cid."</h3>";
		} else {
			print_r($stmt->errorInfo());
		}
	}
}
?>
</h3>
<br><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	Select Contest : <select name="contest">
	<option/>
	<?php
		$query = "SELECT `c_id`, `name` FROM `contest`";
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
    		while($row = $stmt->fetch()) { ?>
			<option value="<?php echo $row['c_id']; ?>"><?php echo $row['c_id']." -  ".$row['name']; ?></option>
	<?php	} ?>
	</select><br><br><br>
	Questions Not added (Click to add):<br><br>
	<?php
		$query = "SELECT `qid` FROM `question` WHERE `contest_id` IS NULL";
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
    		while($row = $stmt->fetch()) { ?>
			<input type="submit" name="submit" value="<?php echo $row['qid']; ?>"><br><br>
	<?php	} ?>
</form>

</body>
</html>
