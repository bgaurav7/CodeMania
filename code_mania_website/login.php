<!DOCTYPE html>
<?php
    ob_start();
?>
<?php
    require_once("includes/functions.php");
    require_once("includes/session.php");
    require_once("includes/header.php");
    require_once("includes/ContentSanitize.class.php");
    $san = new Sanitize();
?>
<?php 
    if (logged_in("user")) {
        header("Location: contest.php");
    }
    if (logged_in("admin")) {
        header("Location: admin/admin.php");
    }
    if (isset($_POST['submit'])) {
	echo "logging in ";
	extract($_POST);
	$uid = $san->cleanString($uid);
	//$uid = mysql_real_escape_string($uid);
	$pwd = $san->cleanString($pwd);
	//$pwd = mysql_real_escape_string($pwd);
	echo $uid . " ...";
	if(chk_admin($uid, $pwd, $dbh) == 1) {
	    $_SESSION['uid'] = $uid;
            $_SESSION['type'] = "admin";
	    $_SESSION['crypted'] = crypt($uid, encryption($uid, $_SESSION['type']));
	    header("Location: admin/admin.php");
	} else if(chk_user($uid, $pwd) == 1) {
	    $_SESSION['uid'] = $uid;
            $_SESSION['type'] = "user";
            $_SESSION['crypted'] = crypt($uid, encryption($uid, $_SESSION['type']));
	    header("Location: contest.php");
        } else {
            header("Location: login.php?login_attempt=1");
        }
    } else {
        $uid = "";
        $pwd = "";
    }
?>
<html>
<head>
<meta name="viewport" content="width=1280">
<title>Code Mania</title>
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
	<header id="header"  class="5grid-layout 5grid">
		<div id="row">
			<div id="12u">
				<div id="logo">
					<h1><a href="#">C o d e - M a n i a</a></h1>
					<p>by IIITA</p>
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
	</marquee></strong><br><br>
	</font>
</div>

<?php
	$message = "LOGIN";
	if (isset($_GET['logout']) and $_GET['logout'] == 1) {
	        $message = "You have been successfully logged out.";
	} else if (isset($_GET['login_attempt']) and $_GET['login_attempt'] == 1) {
	    $message = "Unknown Username and Password Combination.";
	}
	print('	<div class="5grid-layout 5grid" id="page-wrapper">
		<div class="row">
			<div class="12u" align="center">
				<section id="pbox2">
					<h3><strong>' .  $message . '</strong></h3>
				</section>
			</div>
		</div>
	</div>');
?>

<div class="5grid-layout 5grid">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<div class="row" align=center>
			<div class="12u">
				<section id="pbox2">
					<span>Username :</span><input type="text" name="uid" required="">
				</section>
			</div>
		</div>
		<div class="row" align=center>
			<div class="12u">
				<section id="pbox2">
					<span>Password :</span><input type="password" name="pwd" required="">
				</section>
			</div>
		</div>
		<br>
		<div class="row" align="center">
			<div class="12u">
				<section id="pbox1">
					<input id="button-style1" name="submit" type="submit" value="Login">
				</section>
			</div>
		</div>
	</form>
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
    ob_end_flush();
?>
