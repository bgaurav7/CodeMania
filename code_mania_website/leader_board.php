<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
    require_once("includes/session.php");
    confirm_both();
    require_once("includes/functions.php");
    require_once("includes/header.php");

    if(isset($_GET['cid'])) {
	//Contest Score board
	$cid = intval($_GET['cid']);
	require_once("includes/validity.php");
	if(!contest_id_chk($cid, $dbh)) header("Location: contest.php?err=ID");
    } else { 
	//Overall Score
	unset($cid);
    }
?>
<html>
<head>
<meta http-equiv="refresh" content="<?php echo $ref_time; ?>">
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
					<h1><a href="<?php if($cid) echo "question.php?cid=" . $cid; else echo "contest.php"; ?>">C o d e - M a n i a</a></h1>
					<p>by IIITA</p>
					<p align="center"><?php if($cid) { ?>SCORE BOARD (<?php echo contest_name($cid, $dbh); ?>) <?php } else { ?> LEADER BOARD <?php } ?></p>
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
if($cid) {
$query = "
SELECT * FROM (
	SELECT @row_num := @row_num + 1 AS rank, uid, count( * ) AS sub, sum( q.points ) AS points, AVG( s.time_taken ) AS time
	FROM submissions s, (SELECT @row_num := 0) r
	JOIN question q
	WHERE s.qid = q.qid AND q.contest_id = :cid
	GROUP BY uid
	ORDER BY points DESC , time ASC
) AS tmp WHERE tmp.uid = :uid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
    $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
} else {
    $query = "
SELECT * FROM (
	SELECT @row_num := @row_num + 1 AS rank, uid, count( * ) AS sub, sum( q.points ) AS points, AVG( s.time_taken ) AS time
	FROM submissions s, (SELECT @row_num := 0) r
	JOIN question q
	WHERE s.qid = q.qid
	GROUP BY uid
	ORDER BY points DESC , time ASC
) AS tmp WHERE tmp.uid = :uid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
}
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if($row = $stmt->fetch()){
        print('
<div class="5grid-layout 5grid" id="page-wrapper">
    <div class="12u" align="center">
	<div class="row">
		<div class="2u">
			<section id="pbox1">
				<h2>Your Rank : </h2>
			</section>
                </div>
                <div class="1u">
                        <section id="pbox3">
				<h2>' . $row['rank'] . '</h2>
			</section>
		</div>
		<div class="2u">
			<section id="pbox1">
				<h2>Submissions : </h2>
			</section>
                </div>
                <div class="2u">
                        <section id="pbox3">
				<h2>' . $row['sub'] . '</h2>
			</section>
		</div>
		<div class="2u">
			<section id="pbox1">
				<h2>Points : </h2>
			</section>
                </div>
                <div class="2u">
                        <section id="pbox3">
				<h2>' . $row['points'] . '</h2>
			</section>
		</div>
	</div>
    </div>
</div>
<br><br>');
    }
?>
<div class="5grid-layout 5grid" id="page-wrapper">
    <div class="12u" align="center">
	<div class="row">
		<div class="2u">
			<section id="pbox1">
				<h2>Rank</h2>
			</section>
		</div>
		<div class="3u">
			<section id="pbox2">
				<h2>Username</h2>
			</section>
		</div>
                <div class="3u">
			<section id="pbox2">
				<h2>Submssions</h2>
			</section>
		</div>
                <div class="3u">
			<section id="pbox3">
				<h2>Points</h2>
			</section>
		</div>
	</div><strong>
<?php
if($cid) {
	$query = "
SELECT @row_num := @row_num + 1 AS rank, uid, count( * ) AS sub, sum( q.points ) AS points, AVG( s.time_taken ) AS time
FROM submissions s, (SELECT @row_num := 0) r
JOIN question q
WHERE s.qid = q.qid AND q.contest_id = :cid
GROUP BY uid
ORDER BY points DESC , time ASC";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':cid', $cid, PDO::PARAM_STR);
} else {
    $query = "
SELECT @row_num := @row_num + 1 AS rank, uid, count( * ) AS sub, sum( q.points ) AS points, AVG( s.time_taken ) AS time
FROM submissions s, (SELECT @row_num := 0) r
JOIN question q
WHERE s.qid = q.qid
GROUP BY uid
ORDER BY points DESC , time ASC";
    $stmt = $dbh->prepare($query);
}
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if($row = $stmt->fetch()){
    print('<div class="row">
		<div class="2u">
			<section id="pbox1">
				<ul class="style">
					<li>' . $row['rank'] . '</li>
				</ul>
			</section>
		</div>
		<div class="3u">
			<section id="pbox2">
				<ul class="style">
					<li>' . user_name($row['uid']) ." ( ".$row['uid']." ) ". '</li>
				</ul>
			</section>
		</div>
                <div class="3u">
			<section id="pbox2">
				<ul class="style">
					<li>' . $row['sub'] . '</li>
				</ul>
			</section>
		</div>
                <div class="3u">
			<section id="pbox3">
				<ul class="style">
					<li>' . $row['points'] . '</li>
				</ul>
			</section>
		</div>
	</div>');
    }
?>
        </strong>
    </div>
</div>

<!-- copyright -->
<div class="5grid-layout 5grid" id="copyright">
	<div class="row">
		<div class="4u">
			<section id="pbox1">
				<img src="lib/images/flogo.png" alt="some_text">
			</section>
		</div>
		<div class="8u">]
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
