<?php
	require_once("../includes/session.php");
	confirm_admin();
	require_once("../includes/header.php");
	require_once("../includes/path.php");
?>
<html>
<body>
<h1>Add Question</h1><br>
<a href="admin.php">Back</a><br><br>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$qid = test_input($_POST["qid"]);
	$setter = test_input($_POST["setter"]);
	$desc = test_input($_POST["desc"]);
	$time = intval($_POST["time"]);
	$points = intval($_POST["points"]);
	$cid = intval($_POST['contest']);

   	if (empty($_POST["qid"]) || empty($_POST["setter"]) || empty($_POST['contest']) || empty($_POST["time"]) || empty($_POST["points"]) || empty($_FILES["que_f"]) || empty($_FILES["in_f"]) || empty($_FILES["out_f"])) {
     		echo "Please fill required fileds";
   	} else {
		$aE = "txt";
		$temp0 = explode(".", $_FILES["que_f"]["name"]);
		$temp1 = explode(".", $_FILES["in_f"]["name"]);
		$temp2 = explode(".", $_FILES["out_f"]["name"]);
		$e0 = end($temp0);
		$e1 = end($temp1);
		$e2 = end($temp2);

		if ($e0 == $aE && $e1 == $aE && $e2 == $aE) {
			if ($_FILES["que_f"]["error"] > 0 || $_FILES["out_f"]["error"] > 0 || $_FILES["in_f"]["error"] > 0) {
				echo "Error in file upload.<br>";
			} else {
				if(move_uploaded_file($_FILES["que_f"]["tmp_name"], "../data/question/".$qid.".txt")
					&& move_uploaded_file($_FILES["out_f"]["tmp_name"], PATH."data/output/".$qid.".txt")
					&& move_uploaded_file($_FILES["in_f"]["tmp_name"], PATH."data/input/".$qid.".txt") ) {
		      			echo "FILE STORED<br>";
$query = "INSERT INTO `question`(`qid`, `setter`, `description`, `time_limit`, `points`, `contest_id`) VALUES (:qid, :setter, :desc, :time, :points, :cid)";
					$stmt = $dbh->prepare($query);
					$stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
					$stmt->bindParam(':setter', $setter, PDO::PARAM_STR);
					$stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
					$stmt->bindParam(':time', $time, PDO::PARAM_INT);
					$stmt->bindParam(':points', $points, PDO::PARAM_INT);
					$stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
					if($stmt->execute())
						echo "<h3>Question Sucesfully Added</h3>";
					else 
						print_r($stmt->errorInfo());
				} else {
					echo "Error in saving files.<br>";
				}
		  	}
		} else {
		  echo "Invalid file";
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
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	Que. id(Must be unique | string): <input type="text" name="qid" value="<?php echo $qid;?>">
	<br><br>
	Setter: <input type="text" name="setter" value="<?php echo $setter;?>">
	<br><br>
	Time Limit(sec): <input type="number" name="time" value="<?php echo $time;?>">
	<br><br>
	Points: <input type="number" name="points" value="<?php echo $points;?>">
   	<br><br>
	Description: <textarea name="desc" rows="5" cols="40"><?php echo $desc;?></textarea>
	<br><br>
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
	</select><br><br>
	Question File(Text):<input type="file" name="que_f" id="que_f">
	<br><br>
	Input File(Text):<input type="file" name="in_f" id="in_f">
	<br><br>
	Output File(Text):<input type="file" name="out_f" id="out_f">
	<br><br>
	<input type="submit" name="submit" value="Submit">
</form>
</body>
</html>
