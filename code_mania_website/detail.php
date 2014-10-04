<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
    require_once("includes/session.php");
    confirm_both();
    require_once("includes/functions.php");
    require_once("includes/header.php");
    require_once("includes/validity.php");
    
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
?>

<html>
<head>
<meta http-equiv="refresh" content="<?php echo $ref_time; ?>">
<meta name="viewport" content="width=1280">
<title><?php echo $qid; ?></title>
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
					<h1><a href="question.php?cid=<?php echo $cid; ?>">C o d e - M a n i a</a></h1>
					<p>by IIITA</p>
					<p align="center"><?php echo user_name($_SESSION['uid']); ?></p>
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
			<div class="12u">
				<div class="5grid-layout 5grid" id="menu">
					<ul>
						<li class="current_page_item"><a href="comment.php?cid=<?php echo $cid; ?>&qid=<?php echo $qid; ?>&n=20">Comment</a></li>
					</ul>
				</div>
			</div>
		</div>
	</header>
</div>

<div class="5grid-layout 5grid" id="page-wrapper">
	<div class="row">
		<div class="12u" align="center">
			<section id="pbox1">
				<h2><?php echo $qid." (Contest - ".contest_name($cid, $dbh).")"; ?></h2>
			</section>
		</div>
	</div>

	<div class="row">
		<div class="12u">
			<section id="pbox1">
				<p><?php
				    $handle = @fopen("data/question/".$qid.".txt", "r");
				    if ($handle) {
				        while (($buffer = fgets($handle)) !== false) {
				            echo $buffer;
        				    echo "<br>";
					}
					if (!feof($handle)) {
					    echo "Error: unexpected fgets() fail\n";
					}
				    fclose($handle);
				    }
				?></p>
			</section>
		</div>
	</div>

	<div class="row">
		<div class="6u">
			<section id="pbox1">
				<?php
				    $query = 'SELECT setter , time_limit FROM `question` WHERE qid LIKE :qid';
				    $stmt = $dbh->prepare($query);
				    $stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
				    $stmt->execute();
				    $stmt->setFetchMode(PDO::FETCH_ASSOC);
				    $row = $stmt->fetch();
				    print('<p>Time limit : ' . $row['time_limit'] . '</p><BR><BR>
						<p><strong>Setter :</strong></p>
						<p>' . $row['setter'] . '</p>');
				?>
			</section>
		</div>
		<div class="6u" align="right">
			<section id="pbox3">
<input id="button-style1" type="button" value="Solve" onclick=location.href="code.php?cid=<?php echo $cid; ?>&qid=<?php echo $qid; ?>">
			</section>
		</div>
	</div>
</div>

<div class="5grid-layout 5grid" id="copyright">
	<div class="row">
		<div class="4u">
			<section id="pbox1">
				<img src="lib/images/flogo.png" alt="some_text">
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
    ob_end_flush();
?>
