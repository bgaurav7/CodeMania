<?php
       function contest_id_chk($cid, $dbh) {
		$query = "SELECT * FROM contest WHERE c_id = :cid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->rowCount();	
		if (($row) == 1) {
			return 1;
		} else {
			return 0;
		}
	}

	function que_id_chk($cid, $qid, $dbh) {
		$query = "SELECT * FROM question WHERE contest_id = :cid AND qid = :qid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
		$stmt->bindParam(':qid', $qid, PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->rowCount();
		if (($row) == 1) {
			return 1;
		} else {
			return 0;
		}
	}

?>
