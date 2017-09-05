<?php
if (get_magic_quotes_gpc())
{
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
    $_GET = _stripslashes_rcurs($_GET);
    $_POST = _stripslashes_rcurs($_POST);
}
if(isset($_POST['Save']))
{
	include("config.php");
	$ShortDesc =  mysql_real_escape_string($_POST['ShortDesc']);
	$Source = mysql_real_escape_string($_POST['Source']);
	$Name =  mysql_real_escape_string($_POST['Name']);
	$Ext =  mysql_real_escape_string($_POST['Ext']);
	$SearchGroup =  mysql_real_escape_string($_POST['SearchGroup']);
	$SaveCheck = "SELECT * FROM main WHERE Name = '$Name' AND Ext = '$Ext' AND SearchGroup = '$SearchGroup' LIMIT 1";
	$result2 = mysql_query($SaveCheck);
	if (mysql_num_rows($result2) > 0){
			$ErrorMessage = "**Duplication record(Name: $Name, Ext: $Ext, Group: $SearchGroup)**";
	}
	ELSE {
			mysql_query("INSERT INTO main(ShortDesc,Source,Name,Ext,SearchGroup) VALUES('$ShortDesc', '$Source', '$Name','$Ext','$SearchGroup')");
			$GetDisplay = "SELECT * FROM main where Name = '$Name' AND Ext = '$Ext' AND SearchGroup = '$SearchGroup' LIMIT 1";
			include("RecordSet.php");
			if($Ext == "php") $file = "PHP/".$Name.".".$Ext;
			ELSE IF ($Ext == "html" || $Ext == "htm") $file = "HTML/".$Name.".".$Ext;
			ELSE IF ($Ext == "js") $file = "scripts/".$Name.".".$Ext;
			if($Ext == "php" || $Ext == "html" || $Ext == "htm" || $Ext == "js") file_put_contents($file, $Source);
			$inTwoMonths = 60 * 60 * 24 * 60 + time();
			setcookie( "searchID2", $searchID, $inTwoMonths, "", "", false, true );
			mysql_close();
echo <<<_END
<script type="text/javascript">
window.open( 'ResultDisp.php', '_top');
</script>
_END;

	} /* SaveCheck.RecordCount */	
}
?> 