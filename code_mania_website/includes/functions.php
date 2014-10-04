<?php
    function chk_admin($uid, $pass, $dbh) {
	$query = "SELECT * FROM admin WHERE admin_id = :username";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':username', $uid, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->rowCount();
	if (($row) == 1) {
		return chk_user($uid, $pass);
	} else {
		return 0;
	}
    }
    
    function chk_user($uid, $pwd) {
	if ($pwd) {
		$ds = ldap_connect("172.31.1.42");
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		$a = ldap_search($ds, "dc=iiita,dc=ac,dc=in", "uid=$uid" );
		$b = ldap_get_entries( $ds, $a );
		if(isset($b[0])) {
			echo "connect";
			$dn = $b[0]["dn"];
			$ldapbind=@ldap_bind($ds, $dn, $pwd);
			if ($ldapbind) {
				return 1;
			} else {
				return 0;
			}
		}
		ldap_close($ds);
	} else {
		return 0;
	}
	return 0;
    }

    function user_name($uid) {
	$ds = ldap_connect("172.31.1.42");
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	$a = ldap_search($ds, "dc=iiita,dc=ac,dc=in", "uid=$uid");
	$b = ldap_get_entries($ds, $a);
	if(isset($b[0])) {
		return $b[0]['gecos'][0];
	} else {
		return $uid;
	}
	ldap_close($ds);
    }

    function contest_name($cid, $dbh) {
		$query = "SELECT name FROM contest WHERE c_id = :cid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':cid', $cid, PDO::PARAM_INT);
		$stmt->execute();
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $row['name'];
		} else {
			return $cid;
		}
	}

    function encryption($username, $type) {
	$fingerprint = $type . md5($_SERVER['HTTP_USER_AGENT']);
	return $salt = $fingerprint . session_id();
    }
?>
