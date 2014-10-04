<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
	if(isset($_GET['err'])) {
		require_once("includes/ContentSanitize.class.php");
		$san = new Sanitize();
		$err = $san->cleanString($_GET['err']);
		if($err == "ID") echo "<script>alert('Redirection error.');</script>";
		else if($err == "TIME") echo "<script>alert('You are not allowed to access this content.');</script>";
	}	
?>
<?php
    require_once("includes/session.php");
    confirm_both();
    require_once("includes/functions.php");
    require_once("includes/header.php");
?>
<html>
<head>
<meta http-equiv="refresh" content="<?php echo $ref_time; ?>">
<meta name="viewport" content="width=1280">
<title>Contests</title>
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
					<h1><a href="#">C o d e - M a n i a</a></h1>
					<p>by IIITA</p>
					<p align="center"><?php echo user_name($_SESSION['uid']); ?></p>
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
			<div class="12u">
				<div class="5grid-layout 5grid" id="menu">
					<ul>
						<li class="current_page_item"><a href="leader_board.php">LeaderBoard</a></li>
					</ul>
				</div>
			</div>
		</div>
	
	</header>
	<font size="3"><strong>
	<marquee>
		<?php
			$query = "SELECT notice FROM notice";
    			$stmt = $dbh->prepare($query);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
    			if($row = $stmt->fetch()) {
				print($row['notice']);
			}
    			while($row = $stmt->fetch()) {
				print(' &nbsp;&nbsp; | &nbsp;&nbsp; ' . $row['notice']);
			}
		?>
	</marquee><br><br>
	</font></strong><br><br>
</div>
<div class="5grid-layout 5grid" id="page-wrapper">
    <div class="8u">
	<div class="row">
		<div class="6u">
			<section id="pbox1">
				<h2>Contest Name</h2>
			</section>
		</div>
		<div class="3u">
			<section id="pbox2">
				<h2>Start Time</h2>
			</section>
		</div>
		<div class="3u">
			<section id="pbox3">
				<h2>End time</h2>
			</section>
		</div>
	</div>
	<strong>
<!-- CONTESTS -->
<?php
    $query = "SELECT * FROM `contest` ORDER BY start_time ASC";
    $stmt = $dbh->prepare($query);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    while($row = $stmt->fetch()){
    print('<div class="row">
		<div class="6u">
			<section id="pbox1">
					<ul class="style">
						<li>
							<p><a href="question.php?cid=' . $row['c_id'] . '">' . $row['name'] . '</a></p>
						</li>
					</ul>
			</section>
		</div>
		<div class="3u">
			<section id="pbox2">
				<ul class="style">
					<li>' . $row['start_time'] . '</li>
				</ul>
			</section>
		</div>
		<div class="3u">
			<section id="pbox3">
				<ul class="style">
					<li>' . $row['end_time'] . '</li>
				</ul>
			</section>
		</div>
	</div>');
}
?>
    </strong>
    </div>
    
    <div class="3u" align="center">
	<section id="pbox1">
	    <h2>Performance</h2>
		<font size="4"><strong>
	    <?php
		echo "Overall Rank : ";
		$query = "
SELECT rank FROM (
	SELECT @row_num := @row_num + 1 AS rank, uid, count( * ) AS sub, sum( q.points ) AS points, AVG( s.time_taken ) AS time
	FROM submissions s, (SELECT @row_num := 0) r
	JOIN question q
	WHERE s.qid = q.qid
	GROUP BY uid
	ORDER BY points DESC , time ASC
) AS tmp WHERE tmp.uid = :uid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
    		$stmt->execute();
    		if($row = $stmt->fetch()){
		    echo $row['rank'];
		} else {
		    echo "∞";
		}
	    ?></strong></font>
	    <br><br><br><br>
	    <ul class="style">
		    <li><strong>Recently Answered Questions</strong></li>
	    </ul>
	    <?php
		$query = "SELECT qid FROM `submissions` WHERE uid=:uid ORDER BY `time` DESC LIMIT 5";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_STR);
    		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
    		while($row = $stmt->fetch()){
		    print('
			<ul class="style">
			    <li><a href="detail.php?qid=' . $row['qid'] . '">' . $row['qid'] . '</a></li>
			</ul>');
		}
	    ?>
	</section>
    </div>
</div>

<!-- TOP RANKERS -->
<div class="5grid-layout 5grid" id="rank">
	<div class="row">
	    <?php
		$query = "SELECT uid, sum(q.points) AS points, AVG(s.time_taken) AS time FROM submissions s join question q WHERE s.qid = q.qid GROUP BY uid ORDER BY points DESC, time ASC LIMIT 6";
		$stmt = $dbh->prepare($query);
    		$stmt->execute();
		$i = 1;
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
    		while($row = $stmt->fetch()){
		    print('<div class="2u">
				<h2>Rank ' . $i . '</h2>
				<li class="style1"> ' . user_name($row['uid']) ." (".$row['uid'].')  </li>
			</div>');
			$i++;   
		}
	    ?>
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
    ob_end_flush();
?>
