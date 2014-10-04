<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
    require_once("includes/session.php");
    confirm_logged_in();

    if($_SESSION['uid'] != "IIT2012149") {
	header("Location: question.php");
	die();
    }

    require_once("includes/functions.php");
    require_once("includes/header.php");
?>

<?php
    if (isset($_GET['ref']) && intval($_GET['ref']) == 0) {
    } else {
	$query = "DELETE FROM `rank`;";
        $r = mysql_query($query);
        confirm_query($r);
	
	$query = "set @row_num = 0;";
        $r = mysql_query($query);
        confirm_query($r);
	
        $query = "
INSERT INTO `rank`(`rank`, `uid`, `submissions`, `avg_time`) 
SELECT @row_num := @row_num + 1, tmp.uid, tmp.`submissions`, tmp.`avg_time` 
FROM ( 
    SELECT uid , count(*) as submissions, CAST(avg(TIMEDIFF(time, '2014-04-13 10:00:00')) AS time) as avg_time 
    FROM submissions 
    GROUP BY uid 
    ORDER BY submissions DESC, avg_time ASC 
) AS tmp;";
        $r = mysql_query($query);
        confirm_query($r);
    }
?>

<html>
<head>
<meta http-equiv="refresh" content="30">
<meta name="viewport" content="width=1280">
<title>ADMIN</title>
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
					<p align="center">RECENT ACTIVITY</p>
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
						<li class="current_page_item">
							<?php
							    if (isset($_GET['ref']) && intval($_GET['ref']) == 0) {
								echo '<a href="?ref=1">START RANK UPDATE';
							    } else {
								echo '<a href="?ref=0">STOP RANK UPDATE';
							    }
							?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	
	</header>
</div>

<div class="5grid-layout 5grid" id="page-wrapper">
    <div class="6u">
	<div class="row">
		<div class="4u">
			<section id="pbox1">
				<h2>Username</h2>
			</section>
		</div>
		<div class="4u">
			<section id="pbox2">
				<h2>Question</h2>
			</section>
		</div>
		<div class="4u">
			<section id="pbox3">
				<h2>Time Taken</h2>
			</section>
		</div>
	</div>
<?php
    $query = "SELECT `uid`, `qid`, TIMEDIFF(time, '{$start_time}') AS time FROM `submissions` ORDER BY `time` DESC LIMIT 50";
    $r = mysql_query($query);
    confirm_query($r);
    while($row = mysql_fetch_array($r)){
    print('<div class="row">
		<div class="4u">
			<section id="pbox1">
				<ul class="style">
					<li>' . $row['uid'] . '</li>
				</ul>
			</section>
		</div>
		<div class="4u">
			<section id="pbox2">
				<ul class="style">
					<li>' . $row['qid'] . '</li>
				</ul>
			</section>
		</div>
		<div class="4u">
			<section id="pbox3">
				<ul class="style">
					<li>' . $row['time'] . '</li>
				</ul>
			</section>
		</div>
	</div>');
    }
?>
    </div>
    
    <div class="1u"><div class="row"><div class="12u" ></div></div></div>
    
    <div class="4u" align="center">
	<div class="row">
		<div class="6u">
			<section id="pbox1">
				<h2>Question</h2>
			</section>
		</div>
		<div class="6u">
			<section id="pbox2">
				<h2>Successful Subissions</h2>
			</section>
		</div>
	</div>

<?php
    $query = "SELECT `qid`, `submissions` FROM `question` ";
    $r = mysql_query($query);
    confirm_query($r);
    while($row = mysql_fetch_array($r)){
    print('<div class="row">
		<div class="6u">
			<section id="pbox1">
				<ul class="style">
					<li>' . $row['qid'] . '</li>
				</ul>
			</section>
		</div>
		<div class="6u">
			<section id="pbox2">
				<ul class="style">
					<li>' . $row['submissions'] . '</li>
				</ul>
			</section>
		</div>
	</div>');
    }
?>
    </div>
</div>

<!-- TOP RANKERS -->
<div class="5grid-layout 5grid" id="rank" align="center">
	<div class="row">
	    <?php
		$query = "SELECT * FROM `rank` ORDER BY rank ASC LIMIT 11";
		$r = mysql_query($query);
		confirm_query($r);
		
		while($row = mysql_fetch_array($r)){
		    print('<div class="2u">
				<h2>Rank ' . $row['rank'] . '</h2>
				<li class="style1"> ' . $row['uid'] . '</li>
			</div>');
		    
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
    mysql_close($connection);
    ob_end_flush();
?>
