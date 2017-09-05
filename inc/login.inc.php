<?php
// class SimpleLoginSystem
class SimpleLoginSystem {

    // variables
    var $aExistedMembers; // Existed members array
	var $aExistedUsers; // Existed User Name array
    // constructor
    function SimpleLoginSystem($owner_email) {
		include("config.php");
		$GetUser = "SELECT * FROM user WHERE email_address = '$owner_email'";
		$result = mysql_query($GetUser);
		echo mysql_error();
		while($row = mysql_fetch_array($result))
		{
		 $username=$row['first_name'];
		 $email_address = $row['email_address'];
		 $password =  $row['password'];
		 $this->aExistedMembers = "$password";
		 $this->aExistedUsers = "$username";
		}
    }

    function getLoginBox() {
        ob_start();
        $this->simple_login($this->aExistedUsers, $this->aExistedMembers);
		//return 'Hello ' . $this->aExistedUsers . '! ' . $sLogoutForm;
    }

    function simple_login($sName, $sPass) {
        $this->simple_logout();

        $sMD5Password = MD5($sPass);

        $iCookieTime = time() + 24*60*60*30;
        setcookie("member_name", $sName, $iCookieTime, '/');
        $_COOKIE['member_name'] = $sName;
		setcookie("user_name", $this->aExistedUsers, $iCookieTime, '/');
        $_COOKIE['user_name'] = $this->aExistedUsers;
        setcookie("member_pass", $sMD5Password, $iCookieTime, '/');
        $_COOKIE['member_pass'] = $sMD5Password;
    }

    function simple_logout() {
        setcookie('member_name', '', time() - 96 * 3600, '/');
        setcookie('user_name', '', time() - 96 * 3600, '/');
        setcookie('member_pass', '', time() - 96 * 3600, '/');

        unset($_COOKIE['member_name']);
		unset($_COOKIE['user_name']);
        unset($_COOKIE['member_pass']);
    }
    function check_login($sPass) {
        return ($this->aExistedMembers == $sPass);
    }
	
}

?>
