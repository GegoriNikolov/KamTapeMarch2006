<?php 
require "needed/start.php";
force_login();

if($_SERVER["REQUEST_METHOD"] != "POST") { header("Location: my_videos_upload.php"); }
if (!isset($_POST['field_myvideo_descr'], $_POST['field_myvideo_keywords'], $_POST['field_myvideo_title'])) { header("Location: my_videos_upload.php"); }
$word_count = unique_word_count($_POST['field_myvideo_keywords']);
if ($word_count < 3) {
  redirect("my_videos_upload.php?tags");
}
if (strlen($_POST["field_myvideo_title"]) < 2) {
   redirect("my_videos_upload.php?title"); 
}
if (empty($_POST["field_myvideo_descr"])) {
   redirect("my_videos_upload.php?desc"); 
}
if(isset($_POST['addr_yr'], $_POST['addr_day'], $_POST['addr_month'])) {

    // Validate input values
    if (is_numeric($_POST['addr_yr']) && is_numeric($_POST['addr_day']) && is_numeric($_POST['addr_month'])) {
        // Check if the input values represent a valid date
        if (checkdate($_POST['addr_month'], $_POST['addr_day'], $_POST['addr_yr'])) {
            // Create a DateTime object with the given values
            $date = DateTime::createFromFormat('Y-m-d', $_POST['addr_yr'] . '-' . $_POST['addr_month'] . '-' . $_POST['addr_day']);
            
            if ($date !== false) {
                $formattedDate = $date->format('Y-m-d');
            } else {
               header("Location: my_videos_upload.php");
            }
        } else {
            header("Location: my_videos_upload.php");
        }
    } else {
    }
}
?>
<script>
function UploadHandler()
{
	var upload_button = document.uploader.upload;

    var fileInput = document.getElementById("fileToUpload");
    
    var go_ahead = 1;

    if (fileInput.files.length === 0) {
        var go_ahead = 0;
        alert("It looks like you didn't upload a video.");
    }
    if (go_ahead === 1) {
	upload_button.disabled='true';
	upload_button.value='Uploading...';

	return true;
    } else {
    upload_button.value='Try Again';
    return false; // Make sure it doesn't process the request
    }
}
</script>
<div id="mainContent" style="width: 500px;">
	<span class="xxlargeText"><b>Video Upload (Step 2 of 2)</b></span><br><br>
		<table width="100%" cellpadding="4" cellspacing="0" border="0">
			<form method="post" action="my_videos_post.php" enctype="multipart/form-data" id="uploader" name="uploader" onsubmit="return UploadHandler();">

<input type="hidden" name="field_upload_title" value="<?php echo htmlspecialchars($_POST["field_myvideo_title"]); ?>" hidden>
<input type="hidden" name="field_upload_description" value="<?php echo htmlspecialchars($_POST["field_myvideo_descr"]); ?>" hidden>
<input type="hidden" name="field_upload_tags" value="<?php echo htmlspecialchars($_POST["field_myvideo_keywords"]); ?>" hidden>
<?php if(isset($formattedDate)) { ?>
<input type="hidden" name="addr_date" value="<?php echo htmlspecialchars($formattedDate); ?>" hidden>
<?php } ?>
<? if(isset($_POST['field_upload_country'])) { ?>
<input type="hidden" name="field_upload_country" value="<?php echo htmlspecialchars($_POST['field_upload_country']); ?>" hidden>
<?php } ?>
<?php if(isset($_POST['field_upload_country'])) { ?>
<input type="hidden" name="field_upload_address" value="<?php echo htmlspecialchars($_POST['field_upload_address']); ?>" hidden>
<?php } ?>
	<tr>
		<td width="50" align="right" valign="top"><span class="formLabel">File:</span></td>
		<td>
		<div width="595" height="20" cellpadding="0" border="0" bgcolor="#E5ECF9" class="formHighlight">
			<!--<input type="file" style="margin-bottom: 3px" id="fileToUpload" name="fileToUpload" accept="video/mp4,video/x-m4v,video/*"><br>-->
			<object width="470" height="90"><param name="movie" value="/uploader.swf"></param><param name="wmode" value="transparent"></param><embed src="/uploader.swf" type="application/x-shockwave-flash" wmode="transparent" width="470" height="90"></embed></object><br>
			<span class="formHighlightText"><a href="//example.com">Click here</a> if you are having problems with the uploader</span><br><br>
			<span class="formHighlightText"><b>Max file size: 100 MB. Max length: 10 minutes.</b></span><br><br>
			<span class="formHighlightText"><b>Do not upload copyrighted, obscene or any other material which violates YouTube's Terms of Use.</b></span><br><br>
		</div>
	</tr>
	<tr>
		<td width="50" align="right" valign="top"><span class="formLabel">Broadcast:</span></td>
		<td>

                <input type="radio" name="private" value="1">
                <label for="1"><strong>Public</strong>: Share your video with the world! (Recommended)</label><br>
                <input type="radio" name="private" value="2">
                <label for="2"><strong>Private</strong>: Only viewable by you and those you choose.</label><br>
				<div class="topMar5" style="margin-left: 24px;"><div class="formHighlightText"><b>To email this video and enable access now, choose a contact list.</b></div>
				<input type="checkbox" name="contactlist" value="family">
				<label for="family">Family</label><br>
				<input type="checkbox" name="contactlist" value="friends">
				<label for="friends">Friends</label><div>
		</td>
	</tr>
		<tr>
		<td></td>
		<td>
                <div class="largeText"><b>Do not upload any TV shows, music videos, music concerts, or commercials without permission unless they consist entirely of content you created yourself.</b></div>
				<br>
				<div class="formHighlightText">By clicking "Upload Video," you are representing that this video does not violate YouTube's <a href="/t/terms">Terms of Use</a> and that you own all copyrights in this video or have express permission from the copyright owner(s) to upload it.</div>
				<br>
				<div class="formHighlightText">Read <a href="/t/dmca_policy">Copyright Tips</a> for more information about copyright and YouTube's policy.</div>
				<br>
				<input type="submit" value="Upload Video" name="upload" id="upload">
				<br><br>
				<b>PLEASE BE PATIENT—THIS MAY TAKE SEVERAL MINUTES. <br> ONCE COMPLETED, YOU WILL SEE A CONFIRMATION MESSAGE.</b>
		</td>
	</tr>
</table>
</form>
	</div>
<?php 
require "needed/end.php";
?>