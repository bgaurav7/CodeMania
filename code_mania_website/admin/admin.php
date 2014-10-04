<?php
	require_once("../includes/session.php");
	confirm_admin();
?>
<html>
<body>
<h1>Welcome admin</h1><br><br>

<a href="add_contest.php">ADD CONTEST TO DB</a><br><br>
<a href="add_que.php">ADD QUESTION TO CONTEST</a><br><br>
<!--<a href="link.php">LINK QUE TO CONTEST</a><br><br>-->
<a href="add_admin.php">ADD ADMIN</a><br><br>
<a href="add_notification.php">ADD NOTIFICATION</a><br><br>
<a href="../contest.php">SEE CONTESTS</a><br><br>
<br><br>
<a href="../logout.php">Logout</a><br><br>

</body>
</html>
