<?php
class SimpleChat {

    // DB variables
    var $sDbName;
    var $sDbUser;
    var $sDbPass;
	var $lastid;
    // constructor
    function SimpleChat() {
        //mysql_connect("localhost","username","password");
		include("config.php");
		include("GlobalVar.inc.php");
        $this->sDbName = $database_tds;
        $this->sDbUser = $username_tds;
        $this->sDbPass = $password_tds;
		$this->lastid = '';
		$this->MyId = '|' . $GV_id . '|';
    }

    // adding to DB table posted message
    function acceptMessages() {
		include("GlobalVar.inc.php");
		require_once('PHP/sethash.php');
		
        if ($_COOKIE['member_name']) {
            if(isset($_POST['s_say']) && $_POST['s_message']) {
                $sUsername = $_COOKIE['user_name'];

                $vLink = mysql_connect("localhost", $this->sDbUser, $this->sDbPass);

                mysql_select_db($this->sDbName);

                $sMessage = mysql_real_escape_string($_POST['s_message']);
                if ($sMessage != '') {
						$sMessage = newencode($sMessage);
						$SaveCheck = "SELECT * FROM talk_message WHERE message = '$sMessage' LIMIT 1";
						$CheckResult = mysql_query($SaveCheck);
						if (mysql_num_rows($CheckResult) == 0){
								mysql_query("INSERT INTO `talk_message` SET `message`='{$sMessage}'");
						}				
						$idResult = mysql_query("SELECT id FROM talk_message WHERE  message = '$sMessage' order by id desc LIMIT 1");
						while($row = mysql_fetch_array($idResult))
						{
								 $msg_id = $row['id'];
						}				
					$query="SELECT * FROM view_permission where owner_email = '$GV_email_address' and is_active = 3 order by viewer_email";  // query string stored in a variable
					$result=mysql_query($query);          // query executed 
					echo mysql_error();              // if any error is there that will be printed to the screen 
					$TalkList = '|' . $GV_id . '|';
					while($row2 = mysql_fetch_array($result))
					{
						$queryTalk="SELECT * FROM user where email_address = '$row2[viewer_email]' and (is_active = 3 or is_active = 4) limit 1;";  // query string stored in a variable
						$resultTalk=mysql_query($queryTalk);
						if (@mysql_num_rows($resultTalk) != 0){
							while($row3 = mysql_fetch_array($resultTalk))
							{
							 $TalkList = $TalkList . $row3['id'] . "|";
							}					
						}
					}
					if($TalkList != '|' . $GV_id . '|')	{			
					mysql_query("INSERT INTO `s_chat_messages` SET `user`='{$sUsername}', `msg_id`={$msg_id}, `when`=UNIX_TIMESTAMP(),`talk_to`='{$TalkList}'");
					}
				}

                mysql_close($vLink);
            }
        }

        ob_start();
        require_once('chat_input.php');
        $sShoutboxForm = ob_get_clean();

        return $sShoutboxForm;
    }

    function getMessages() {
		require_once('PHP/sethash.php');
        $vLink = mysql_connect("localhost", $this->sDbUser, $this->sDbPass);

        //select the database
        mysql_select_db($this->sDbName);

			//returning the last 15 messages
			$vRes = mysql_query("SELECT * FROM `s_chat_messages` where talk_to like '%$this->MyId%' ORDER BY `id` DESC LIMIT 15");

			$sMessages = '';
			$Msg_Output = '';
			// collecting list of messages
			if ($vRes) {
				while($aMessages = mysql_fetch_array($vRes)) {
				        
						$GetMsg = mysql_query("SELECT * FROM talk_message WHERE id = $aMessages[msg_id] LIMIT 1");
						while($row4 = mysql_fetch_array($GetMsg))
						{
								 $Msg_Output = newdecode($row4['message']);
						}				
				
				
					$sWhen = date("M j h:i:s A", $aMessages['when']);
					$sMessages = '<div class="message">' . $aMessages['user'] . '<span>(' . $sWhen . ')</span>'. ': ' . $Msg_Output.' </div>'.$sMessages;
				}
			} else {
				$sMessages = 'DB error, create SQL table before';
			}

			mysql_close($vLink);

			ob_start();
			require_once('chat_begin.html');
			echo $sMessages;
			require_once('chat_end.html');
			return ob_get_clean();
    }
}

?>
