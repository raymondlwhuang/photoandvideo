<?php

class Function_model extends CI_model {
	function SureRemoveDir($dir, $DeleteMe) {
		if(!$dh = @opendir($dir)) return;
		while (false !== ($obj = readdir($dh))) {
			if($obj=='.' || $obj=='..') continue;
			if (!@unlink($dir.'/'.$obj)) SureRemoveDir($dir.'/'.$obj, true);
		}

		closedir($dh);
		if ($DeleteMe){
			@rmdir($dir);
		}
	}
	function getBrowser(){
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		if (preg_match('/linux/i', $u_agent)){
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)){
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)){
			$platform = 'windows';
		}
		// Next get the name of the useragent yes seperately and for good reason

		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent)){
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent)){
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent)){
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent)){
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent)){
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		// finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>'.join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			if (!preg_match_all($pattern, $u_agent, $matches)){
			// we have no matching number just continue
			}
			// see how many we have
			$i = count($matches['browser']);
			if ($i != 1){
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else{
				$version= $matches['version'][1];
			}
		 }
		 else{
			$version= $matches['version'][0];
		 }
		 // check if we have a number
		 if ($version==null || $version=="") {$version="?";}
			return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
			);
	} 
	
	function getIpAddr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		// Check if the IP is passed from a proxy.
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
    function _stripslashes_rcurs($variable, $top = true)
    {
        $clean_data = array();
        foreach ($variable as $key => $value)
        {
            $key = ($top) ? $key : stripslashes($key);
            $clean_data[$key] = (is_array($value)) ?
                stripslashes_rcurs($value, false) : stripslashes($value);
        }
        return $clean_data;
    }
	function str_rot($s, $n = 18) {
		$n = (int)$n % 26;
		if (!$n) return $s;
		for ($i = 0, $l = strlen($s); $i < $l; $i++) {
			$c = ord($s[$i]);
			if ($c >= 97 && $c <= 122) {
				$s[$i] = chr(($c - 71 + $n) % 26 + 97);
			} else if ($c >= 65 && $c <= 90) {
				$s[$i] = chr(($c - 39 + $n) % 26 + 65);
			}
		}
		return $s;
	}
	function newencode($original,$n = 18)
	{
	   return $this->function_model->str_rot(base64_encode($original),$n);
	}
	function newdecode($original,$n = -18)
	{
		return base64_decode($this->function_model->str_rot($original,$n));
	}
	function UserSetup($email_address) {
		$email_address = strtolower($email_address);
		$ip=$this->function_model->getIpAddr();
		$todaydate = date("l, F j, Y, g:i a");
		$sendinfo = $email_address.':::'.date('Ymd').'000000';
		$userEmail = newencode($sendinfo);
		$to = "$email_address";
		$headers = "From: no-reply@raymondlwhuang.com\r\n";
		$headers .= "Reply-To: no-reply@raymondlwhuang.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = '<html><body>';	
		$password = '';
		$get=$this->user->_get1(array('email_address'=>$email_address));
		if ($get){
			$first_name=ucfirst(strtolower($get->first_name));
			$last_name = ucfirst(strtolower($get->last_name)); 
			$password = $get->password;
		}
		if($password != ''){
			$subject = "Password reset required";
			$message .= "<h1>Hi $first_name $last_name,</h1>";
			$message .= "<br/><br/>You require a password reset to this email address: $email_address <br/>
			Click here to <a href='http://www.raymondlwhuang.com/UserSetup?UserSetup=$userEmail' > Reset your password</a><br/>
			If you forgot the password for one of these usernames, just re-type in all information for this e-mail address,<br/>
			Hope this helps.<br/><br/><br/>
			See you back on www.raymondlwhuang.com!<br/><br/><br/>";
		}
		else {
			$subject = "Account Setup Notification";
			$message .= "<h1>User Setup Notification,</h1>";
			$message .= "<br/><br/>Please Click here to <a href='http://www.raymondlwhuang.com/UserSetup?UserSetup=$userEmail' > Set up your account</a><br/>
			Hope this helps.<br/><br/><br/>
			See you back on www.raymondlwhuang.com!<br/><br/><br/>";	
		}
		$message .= '</body></html>';
		if (preg_match("/bcc:/i", $userEmail . " " . $message) == 0 &&          /* check for injected 'bcc' field */
			preg_match("/Content-Type:/i", $userEmail . " " . $message) == 0 && /* check for injected 'content-type' field */
			preg_match("/cc:/i", $userEmail . " " . $message) == 0 &&           /* check for injected 'cc' field */
			preg_match("/to:/i", $userEmail . " " . $message) == 0) {           /* check for injected 'to' field */
			// Format the body of the email
			$message = $message . "\n\nSent from: $ip ($todaydate)\n";
			// Set the header, include the ip and set the reply-to field for convenience when replying to the email
	//		$headers = "CC: $cc\nX-Sender-IP: $ip\nFrom: $email\nReply-To: $email";
			// Send the email and check the result whether the function call was successful or not
			$sent = mail($to, $subject, $message, $headers) ;
			if($sent) {
				$ErrorMessage="Please check your mail and <br/>See you back on www.raymondlwhuang.com!";
			} else {
				$ErrorMessage="We encountered an error sending your mail";
			}
		} else  {
			$ErrorMessage="We encountered an error sending your mail";
		}
		return $ErrorMessage;
	}
	function getFiles($path) {
	   //Function takes a path, and returns a numerically indexed array of associative arrays containing file information,
	   //sorted by the file name (case insensitive).  If two files are identical when compared without case, they will sort
	   //relative to each other in the order presented by readdir()
	   $files = array();
	   $fileNames = array();
	   $i = 0;
	  
	   if (is_dir($path)) {
		   if ($dh = opendir($path)) {
			   while (($file = readdir($dh)) !== false) {
				   if ($file == "." || $file == "..") continue;
				   $fullpath = $path . "/" . $file;
				   $fkey = strtolower($file);
				   while (array_key_exists($fkey,$fileNames)) $fkey .= " ";
				   $a = stat($fullpath);
				   $files[$fkey]['size'] = $a['size'];
				   if ($a['size'] == 0) $files[$fkey]['sizetext'] = "-";
				   else if ($a['size'] > 1024) $files[$fkey]['sizetext'] = (ceil($a['size']/1024*100)/100) . " K";
				   else if ($a['size'] > 1024*1024) $files[$fkey]['sizetext'] = (ceil($a['size']/(1024*1024)*100)/100) . " Mb";
				   else $files[$fkey]['sizetext'] = $a['size'] . " bytes";
				   $files[$fkey]['name'] = $file;
				   $files[$fkey]['type'] = filetype($fullpath);
				   $fileNames[$i++] = $fkey;
			   }
			   closedir($dh);
		   } else die ("Cannot open directory:  $path");
	   } else die ("Path is not a directory:  $path");
	   sort($fileNames,SORT_STRING);
	   $sortedFiles = array();
	   $i = 0;
	   foreach($fileNames as $f) $sortedFiles[$i++] = $files[$f];
	  
	   return $sortedFiles;
	}

	
}