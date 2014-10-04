<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
	require_once("includes/session.php");
	confirm_user();
	require_once("includes/functions.php");
	require_once("includes/header.php");
	require_once("includes/validity.php");
	require_once("includes/path.php");
	
	if (!isset($_GET['qid']) || !isset($_GET['cid'])) {
	    header("Location: contest.php?err=ID");
    	}
	$cid = intval($_GET['cid']);
	
	require_once("includes/ContentSanitize.class.php");
	$san = new Sanitize();    
    	$qid = $san->cleanString($_GET['qid']);
	
	if(contest_id_chk($cid, $dbh) && que_id_chk($cid, $qid, $dbh)) {
		$query = "SELECT `start_time`, `end_time` FROM `contest` WHERE c_id = :cid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$start_time = $row['start_time'];
		$end_time = $row['end_time'];

		if (date('Y-m-d H:i:s') > $end_time || date('Y-m-d H:i:s') < $start_time)
			header("Location: contest.php?err=TIME");
	} else {
		header("Location: contest.php?err=ID");
	}

	if (!isset($_POST['code'])) {
	    header("Location: code.php?cid=". $cid. "&qid=" . $qid);
	}
?>
<html>
<head>
<meta name="viewport" content="width=1280">
<title>Result</title>
<link rel="shortcut icon" href="lib/images/logo.ico"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<link rel="stylesheet" href="lib/core.css">
<link rel="stylesheet" href="lib/style.css">
<link rel="stylesheet" href="lib/core-desktop.css">
<link rel="stylesheet" href="lib/style-desktop.css">
</head>
<body>
<div id="header-wrapper">
	<header id="header" class="5grid-layout 5grid">
		<div id="row">
			<div id="12u">
				<div id="logo">
					<h1><a href="question.php?cid=<?php echo $cid; ?>&qid=<?php echo $qid; ?>">C o d e - M a n i a</a></h1>
					<p>by IIITA</p>
					<p align="center"><?php echo $qid." (Contest - ".contest_name($cid, $dbh)." )"; ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="12u">
				<div class="5grid-layout 5grid" id="menu" align="right">
						<ul>
		    				<li class="current_page_item"><a href="logout.php">Logout</a></li>
						</ul>
				</div>
			</div>
		</div>
	</header>
</div>

<div class="5grid-layout 5grid" id="page-wrapper">
    <div class="12u">
	<div class="row" align="center">
		<div class="8u">
			<section id="pbox2">
			    <br><h2>
<?php
    $uid = $_SESSION['uid'];
    
    $query = "SELECT * FROM `submissions` WHERE uid=:uid and qid=:qid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
    $stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();

    if($stmt->rowCount() > 0) {
        echo "Question Already Answered</h2>";
	$img = "correct.png";
    } else {
	$o_code = PATH."shell/code/" . $uid . "_" . $qid;
	$s_code = $o_code . ".c";

	$fp=fopen("$s_code", "w") or die("Code not uploaded");
	fwrite($fp, $_POST['code']);
	fclose($fp);
	
	$query = 'SELECT time_limit FROM `question` WHERE qid LIKE :qid';
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
	$stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$row = $stmt->fetch();
				    
	$rv=shell_exec("nohup nice -n 10 bash shell/shell.sh ". $uid . " " . $qid . " " . $row['time_limit'] . " " . PATH);
	//echo $rv;
	if($rv == 1) {
	    $md5 = md5_file($s_code);
	    $query = "
INSERT INTO `submissions`(`uid`, `qid`, `md5sum`, `time_taken`)
SELECT :uid AS uid, :qid AS qid, :md5 AS md5sum, CAST(TIMEDIFF(CURRENT_TIMESTAMP, 'start_time') AS time) AS time_taken
FROM contest WHERE c_id = :cid";
	    $stmt = $dbh->prepare($query);
	    $stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
	    $stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
	    $stmt->bindParam(':md5', $md5, PDO::PARAM_STR);
	    $stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
	    $stmt->execute();
	    
	    $query="UPDATE `question` SET `submissions`=`submissions` + 1 WHERE qid=:qid";
	    $stmt = $dbh->prepare($query);
	    $stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
	    $stmt->execute();
	    echo "CORRECT ANSWER</h2>";
	    
	    $img = "success.png";
	} else if($rv == 3) {
	    echo "TIME LIMIT EXCEEDED / RUNTIME ERROR</h2>";
	    $img = "tle.png";
	} else if($rv == 2) {
	    echo "WRONG OUTPUT</h2>";
	    $img = "wrong.png";
	} else if($rv == 0) {
	    echo "Compilation Error</h2>";
	    print('
		    <div class="12u" align="left">
			<section id="pbox1" >
			    <font size="2"> ');
				$error = PATH."shell/error/" . $uid . "_" . $qid . ".txt";
				$handle = @fopen($error, "r");
				    if ($handle) {
				        while (($buffer = fgets($handle)) !== false) {
				            echo $buffer;
        				    echo "<br><br>";
					}
					if (!feof($handle)) {
					    echo "Error: unexpected fgets() fail\n";
					}
				    fclose($handle);
				}
				unlink($error);
			    print('</font>
			</section>
		    </div>');
	    $img = "bug.png";
	}
    }
?>
			</section>
		</div>
		<div class="4u">
			<section id="pbox1">
				<img src="lib/images/<?php echo $img; ?>" alt="eval" width="50%" height="50%">
			</section>
		</div>
		
	</div>
    </div>
    <div class="12u" align="center">
	    <section id="pbox3">
		    <input id="button-style1" type="button" value="BACK" onclick=location.href="detail.php?cid=<?php echo $cid; ?>&qid=<?php echo $qid; ?>">
	    </section>
    </div>
</div>

<div class="5grid-layout 5grid" id="copyright">
	<div class="row">
		<div class="4u">
			<section id="pbox1">
				<img src="lib/images/flogo.png" alt="logo">
			</section>
		</div>
		<div class="8u">
			<br>
			<section>
				<p> © <a href="http://iiita.ac.in/" target="_blank">IIITA</a> | Code Mania | Design: <a href="https://www.facebook.com/yourDAD.gb" target="_blank">Gaurav Bansal</a></p>
			</section>
		</div>
	</div>
</div>
</body>
</html>
<?php
    mysql_close($connection);
?>
<?php
    ob_end_flush();
?>
