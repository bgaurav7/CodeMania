<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
	require_once("includes/session.php");
	confirm_user();
	require_once("includes/header.php");
	require_once("includes/functions.php");
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
?>

<html>
<head>
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
<link rel="stylesheet" href="lib/codemirror/codemirror.css">
<script type='text/javascript' src="lib/codemirror/codemirror.js"></script>
<script type='text/javascript' src="lib/codemirror/matchbrackets.js"></script>
<script type='text/javascript' src="lib/codemirror/clike.js"></script>
</head>
<body>

<div id="header-wrapper">
	<header id="header" class="5grid-layout 5grid">
		<div id="row">
			<div id="12u">
				<div id="logo">
					<h1><a href="detail.php?cid=<?php echo $cid; ?>&qid=<?php echo $qid; ?>">C o d e - M a n i a</a></h1>
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
		</div>
	</header>
</div>

<div class="row">
	<div class="12u" align="center">
		<section id="pbox1">
			<h2><?php echo $qid." (Contest - ".contest_name($cid, $dbh)." )"; ?></h2>
		</section>
	</div>
</div>

<div class="5grid-layout 5grid">
	<form action="eval.php?cid=<?php echo $cid ?>&qid=<?php echo $qid ?>" method="post">
	<div class="row">
		<div class="12u">
			<section id="pbox2">
				<textarea name="code" id="c-code" rows="20" cols="150" align="center" spellcheck='false's>
<?php
$error = PATH."shell/error/" . $_SESSION['uid'] . "_" . $qid . ".c";

if(file_exists($error)) {
    $handle = @fopen($error, "r");
    if ($handle) {
	while (($buffer = fgets($handle)) !== false) {
	    echo $buffer;
	}
    fclose($handle);
    }
    unlink($error);
} else {
print('#include <stdio.h>

int main()
{
  
  return 0;
}');
}
    
?></textarea>
<script>
  var cEditor = CodeMirror.fromTextArea(document.getElementById("c-code"), {
    tabSize: 2,
    lineNumbers: true,
    matchBrackets: true,
    mode: "text/x-csrc"
  });
</script>
			</section>
		</div>
	</div>

	<div class="row" align=right>
		<div class="12u">
			<section id="pbox3">
				<input id="button-style1" type="submit" name="submit" value="Submit">
			</section>
		</div>
	</div>
	</form>
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
    ob_end_flush();
?>
