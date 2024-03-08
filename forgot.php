<?
require "needed/start.php";
$captchaid = generateId(64);
?>
<h1>Forgot Password</h1>

<div class="codeBox" style="float: right;">
	<form method="post" action="/forgot">
	<table class="dataEntryTableSmall" cellpadding="2">
		<tr>
			<td class="formLabelSmall">User Name:</td>
			<td class="formFieldSmall"><input type="text" size="20" name="username" value=""></td>
			<td class="formFieldSmall">&nbsp;</td>
		</tr>
		<input type=hidden name=challenge value=<? echo $captchaid; ?>>
		<tr>
			<td class="formLabelSmall" nowrap><a href="/cimg?c=<? echo $captchaid; ?>" target=_blank>Verification Code:</a></td>
			<td class="formFieldSmall"><input type="text" size="20" name="response"></td>
			<td class="formFieldSmall"><img src=/cimg?c=<? echo $captchaid; ?>></td>
		</tr>
		<tr>
			<td class="formLabelSmall">&nbsp;</td>
			<td class="formFieldSmall"><input type="submit" name="submit" value="Email my password!"></td>
			<td class="formFieldSmall">&nbsp;</td>
		</tr>
	</table>
	</form>
</div>

<h2>Forgot your password? No problem!</h2>
<p>Simply fill out your user name and <a href="/cimg?c=<? echo $captchaid; ?>" target=_blank>verification code</a> we'll send your password to the email address you signed up with.</p>

<? require "needed/end.php"; ?>