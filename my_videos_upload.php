<?php 
require "needed/start.php";
force_login();
if (isset($_GET['tags'])) {
    alert("You need 3 unique tags!", "error");
}

if (isset($_GET['title'])) {
    alert("Title is too short.", "error");
}

if (isset($_GET['desc'])) {
    alert("Please fill in a description.", "error");
}
?>
<div id="sidebarRight245">
<div class="highlightBox">
<a href="/my_profile_mobile" class="largeText"><b>Upload videos directly from your mobile phone!</b></a>
</div>
</div>
	<!--<h1>Video Upload (Step 1 of 2)</h1>-->
	<div id="mainContent">
	<span class="xxlargeText"><b>Video Upload (Step 1 of 2) &nbsp; </b></span>
	<span class="required">(All fields required)</span>
	<br><br>
	<!--<br><br>Uploading a video is a two-step process—on the next page, you'll be able to choose your video file and set the privacy settings.<br><br>-->
	<div class="marB15"><div class="largeText">Uploading a video is a two-step process—on the next page, you'll be able to choose your video file and set the privacy settings.</div></div>
	<h2 class="marB15">Upload Tips</h2>
	<ul class="marB15" style="font-size: 13px">
	<li>Uploads will usually take 1-5 minutes per MB on a high-speed connection.</li>
	<li>Converting your video takes a few minutes; you can add more info or upload more videos while it's processing.</li>
	<li>Videos are limited to 10 minutes (unless you're a Director) and 100 MB.</li>
	<li>Videos saved with the following settings convert the best:</li>
	<ul>
	<li>MPEG4 (Divx, Xvid) format</li>
	<li>320x240 resolution</li>
	<li>MP3 audio</li>
	<li>30 frames per second framerate</li>
	</ul>
	</ul>
		<table width="100%" cellpadding="5" cellspacing="0" border="0">
			<form method="post" name="theForm" action="my_videos_upload_2.php">
			<input type="hidden" name="field_command" value="upload_video">
			<tbody>
				<tr>
					<td width="200" align="right"><span class="formLabel">Title:</span></td>
					<td><input type="text" size="30" maxlength="60" name="field_myvideo_title" autocomplete="on"></td>
				</tr>
				<tr>
					<td align="right" valign="top"><span class="formLabel">Description:</span></td>
					<td><textarea name="field_myvideo_descr" cols="40" rows="4"></textarea></td>
				</tr>
				<tr>
					<td width="200" align="right" valign="top"><span class="formLabel">Tags:</span></td>
					<td><input type="text" size="30" maxlength="60" name="field_myvideo_keywords" autocomplete="on">
					<div class="formFieldInfo"><strong>Enter three or more tags, separated
by spaces.</strong> <br>Tags are keywords used to describe
your video so it can be easily found by other users. <br>For example,
if you have a surfing video, you might tag it: <code>surfing beach
waves</code>.<br></div></td>
				</tr>
				<tr>
					<td align="right" valign="top"><span class="formLabel">Video Category:</span></td>
					<td>
					<div class="floatL" style="margin-right: 18px;">
					<input type="radio" id="myv_chann1" name="field_myvideo_categories" value="1">
					<label for="myv_chann1">Arts &amp; Animation</label><br>
					<input type="radio" id="myv_chann3" name="field_myvideo_categories" value="23">
					<label for="myv_chann3">Comedy</label><br>
					<input type="radio" id="myv_chann5" name="field_myvideo_categories" value="10">
					<label for="myv_chann5">Music</label><br>
					<input type="radio" id="myv_chann7" name="field_myvideo_categories" value="22">
					<label for="myv_chann7">People</label><br>
					<input type="radio" id="myv_chann9" name="field_myvideo_categories" value="26">
					<label for="myv_chann9">Science &amp; Technology</label><br>
					<input type="radio" id="myv_chann11" name="field_myvideo_categories" value="19">
					<label for="myv_chann11">Travel &amp; Places</label>
					</div>
					<div>
					<input type="radio" id="myv_chann2" name="field_myvideo_categories" value="2">
					<label for="myv_chann2">Autos &amp; Vehicles</label><br>
					<input type="radio" id="myv_chann4" name="field_myvideo_categories" value="24">
					<label for="myv_chann4">Entertainment</label><br>
					<input type="radio" id="myv_chann6" name="field_myvideo_categories" value="25">
					<label for="myv_chann6">News &amp; Blogs</label><br>
					<input type="radio" id="myv_chann8" name="field_myvideo_categories" value="15">
					<label for="myv_chann8">Pets &amp; Animals</label><br>
					<input type="radio" id="myv_chann10" name="field_myvideo_categories" value="17">
					<label for="myv_chann10">Sports</label><br>
					<input type="radio" id="myv_chann12" name="field_myvideo_categories" value="20">
					<label for="myv_chann12">Video Games</label>
					</div>
					</td>
				</tr>
				<tr>
					<td align="right"><span class="formLabel">Language:</span></td>
					<td><select name="language">
					<option value="EN" >English</option>
					<option value="ES" >Spanish</option>
					<option value="JP" >Japanese</option>
					<option value="DE" >German</option>
					<option value="CN" >Chinese</option>
					<option value="FR" >French</option>
					</select></td>
				</tr>
				<tr>
					<td></td>
					<td class="largeText"><b>Do not upload copyrighted material for which you don't own the rights or have permission from the owner.</b></td>
				</tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="continue" name="continue" value="Continue ->"></td>
                </tr></form></table>
</div>
<?php 
require "needed/end.php";
?>
