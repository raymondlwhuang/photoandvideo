<script src="/scripts/login.js"></script>
<div id='header'>
<form name="ValidateUser" method="Post" class="block">
	<div style="float:left">
 <input type="image" src="/images/home.png" name="Home" id="Home" value="Home" width="40" onClick="window.open('index',target='_top');" class="block">
	</div>
	<div style="float:left;vertical-align:top;">
		<div id="mydiv">
			<div style="text-align:right">
			<font class="greenfont">Email:</font> <input type="text" name="username" id="username" size="20" maxlength="30" value=""  onChange="document.ValidateUser.password.value = ''"/>
			<br/>
			<font class="greenfont">Pin:</font> <input type="text" name="pin" id="pin" size="20" maxlength="30" value=""  AUTOCOMPLETE=OFF />
			</div>
		</div>
	</div>
	<div style="float:left;vertical-align:top;">
			<div class="block" id="autologin">
			<font class="greenfont">Password:</font><input type="password" name="password" id="password"  size="20" maxlength="15" value="" AUTOCOMPLETE=OFF onfocus="this.value = ''"/><br/>
			<input type="checkbox" name="autologin" value="1"><font class="greenfont">Keep me loged in</font>
			</div>
	</div>
	<div style="float:left">
		<input type="submit" class="submiting" id="login" name="submit" value="Log In" onClick="return formCheck()">
		<button type="submit" name="UserSetup" id="UserSetup" value="UserSetup" class="submiting"  onClick="return validEMail(document.getElementById('username').value);document.ValidateUser.submit();">
			New User<br />
			Reset/Forget password
		</button>
		<input type="submit" class="submiting" value="Introduction" onClick="window.open('Introduction',target='_top');return false;">
	</div>
	<b><font class="redtext" id="ErrorMessage" size="4"><?php if(isset($ErrorMessage)) echo $ErrorMessage; ?></font></b><br/>
   <input type="hidden" name="token" value="<?php echo md5("raymond".date("ymdhis")); ?>" />
</form>
<hr/>
</div>
