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
	require_once("includes/ContentSanitize.class.php");

	$san = new Sanitize();
	if (!isset($_GET['n'])) {
		$n = 50;
	} else {
		$n = intval($_GET['n']);
	}

	if (!isset($_GET['qid']) || !isset($_GET['cid'])) {
	    header("Location: contest.php?err=ID");
	}

	$cid = intval($_GET['cid']);  
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

	if (isset($_POST['comment'])) {
		$comment = $san->cleanString($_POST['comment']);
		$comment = mysql_real_escape_string($comment);
		$comment = $san->cleanString($_POST['comment']);
		$query = "INSERT INTO `comment`(`qid`, `uid`, `comment`) VALUES(:qid, :uid, :comment)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
		$stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->execute();
	}
?>
<html>
<head>
<meta http-equiv="refresh" content="30">
<meta name="viewport" content="width=1280">
<title>Questions</title>
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
					<h1><a href="detail.php?cid=<?php echo $cid; ?>&qid=<?php echo $qid; ?>">C o d e - M a n i a</a></h1>
					<p>by IIITA</p>
					<p align="center"><?php echo $qid; ?></p>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="12u" align="right">
				<div class="5grid-layout 5grid" id="menu">
					<ul>
						<li class="current_page_item"><a href="logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</div>
	
	</header>
</div>

<?php
    $query = "SELECT `uid`, `comment` FROM `comment` WHERE qid=:qid ORDER BY `time` DESC LIMIT :n";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
    $stmt->bindParam(':n', $n, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if($stmt->rowCount() == 0) {
        print('<div class="5grid-layout 5grid" id="page-wrapper">
   <section id="pbox1">No Comments..!!</section>
</div>');
    } else {
        while($row = $stmt->fetch()){
	    $com = nl2br($row['comment']);
            print('<li class="style1"> ' . user_name($row['uid']) . ' ( ' . $row['uid'] . ' ) </li>
<div class="5grid-layout 5grid" id="page-wrapper">
   <section id="pbox1">' . $com . '</section>
</div>');
        }
    }
?>

<div align="center">
    <input id="button-style1" type="button" value="View More" onclick='location.href="comment.php?qid=<?php echo $qid; ?>&n=<?php echo $n+10; ?>"'>
</div>
<br><br>
<div class="5grid-layout 5grid">
	<form action="comment.php?qid=<	?php echo $qid; ?>&n=<?php echo $n; ?>" method="post">
		<div class="row">
		<div class="10u">
			<textarea name="comment" style="width:100%;" required="" rows="3" cols="500" spellcheck='false'></textarea>
		</div>
		<div class="2u">
				<section id="pbox1">
					<input id="button-style1" name="button" type="submit" value="Comment">
				</section>
		</div>
		</div>
	</form>
</div>
</br>

<!-- copyright -->
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
