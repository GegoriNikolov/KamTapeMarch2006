<?php
require "needed/start.php";
if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(empty(trim($_POST['field_contact_email']))) {
		$contact_error = "Please enter an email.";
	} elseif(!filter_var(trim($_POST['field_contact_email']), FILTER_VALIDATE_EMAIL)) {
		$contact_error = "Please enter a valid email.";
    }
    if($_POST['field_contact_subject'] > 7 || $_POST['field_contact_subject'] < 1) {$contact_error = "What is this about?"; }
    $word_count = unique_word_count($_POST['field_contact_message']);
    if ($word_count < 8) {
    $contact_error = "Please provide enough details so we can properly process your e-mail";
    }
    if ($word_count > 620) {
    $contact_error = "Too much words.";
    }
    $cooldown = $conn->prepare(
			"SELECT * FROM tickets
			WHERE sender = ? AND submitted > DATE_SUB(NOW(), INTERVAL 3 HOUR)
			ORDER BY submitted DESC"
		);
        $cooldown->execute([$_POST['field_contact_email']]);
		if($cooldown->rowCount() > 2) {
		$contact_error = "Are you sure you meant to send that 2 times?";
		}
    if(!empty($contact_error)){
    alert($contact_error, "error");
    }
    
    if(empty($contact_error)){
    // Submit the inquiry!
    $sql = "INSERT INTO tickets (sender, subject, message) VALUES (:email, :subject, :message)";

    // Bind the parameters to the prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $_POST['field_contact_email']);
    $stmt->bindParam(":subject", $_POST['field_contact_subject']);
    $stmt->bindParam(":message", encrypt($_POST['field_contact_message']));
  
    // Execute the prepared statement
    try {
    $stmt->execute();
    alert("Thanks for contacting us! We will most likely respond to your message in a few hours.");
    } catch (PDOException $e) {
    alert("Was unable to contact.", "error");
    }
    }

    }
    
?>
<style>
.answerBox {
	margin-bottom: 15px;
}
</style>

<script language="javascript" type="text/javascript">
function checkSubject() {
	if(document.contactForm.contact_subject.value==1) {
		showDiv('customer_service_label');
		showDiv('customer_service_dropdown')
		hideDiv('colleges_label');
		hideDiv('colleges_dropdown')
		} 
	else {
		if(document.contactForm.contact_subject.value==9) {
			showDiv('colleges_label');
			showDiv('colleges_dropdown')
			} 
		hideAllFAQ();
		hideDiv('customer_service_label');
		hideDiv('customer_service_dropdown')
		};
	return false;
	}
	

function prePopMessage() {
	if(document.contactForm.colleges_subject.value=="colleges_0") {
		document.contactForm.contact_message.value = "College Name: \nCollege Mascot: \nCollege Email Domain: \nCity: \nState/Province: \nCountry: \n\nComments: ";
		}
	return false;
	}
	

//Constructor for a question class FAQ_topic[] object
function FAQ_item(question, answerBegin, answerMore) {
	this.question = question;
	this.answerBegin = answerBegin;
	this.answerMore = answerMore;
} 

var FAQ_topic = new Array(4);
FAQ_topic[0] = new Array(6);
FAQ_topic[1] = new Array(4);
FAQ_topic[2] = new Array(3);
FAQ_topic[3] = new Array(7);


FAQ_topic[0][0] = "Video Player Issues";
FAQ_topic[0][1] = new FAQ_item("Video won't play", "All of the videos on YouTube are streamed through a Flash player, so you need", "to have the latest version of Macromedia Flash installed on your computer. To download it, please visit Adobe.");
FAQ_topic[0][2] = new FAQ_item("Video starting and stopping", "When a video stops and starts again it could be because the internet connection", "that you are using is not fast enough to stream a video, or it could also be that our servers are experiencing high traffic affecting the speed of our videos. If the issue is in fact with our servers, there's not much you can do on your end to remedy this. We are constantly bringing in more servers to handle our increasing traffic, so thanks for hanging in there with us!");
FAQ_topic[0][3] = new FAQ_item("No sound", "YouTube supports many different audio and video codecs, but it may be that the audio codec", "you are trying to use in your video is not recognized by YouTube. In this case you might try remaking your video and selecting a different form of compression for your audio.<br />We are continually adding support for more file formats and video/audio compression codecs.");
FAQ_topic[0][4] = new FAQ_item("Javascript or Flash error message", "Occasionally when our site is experiencing heavy traffic, this message can display regardless of whether", " or not you do in fact need to install Flash or enable Javascript.  If this is the case for you, please just try again later using a fresh browser window, or try using another browser altogether.  <br />The other possibility is that you actually don't have Javascript enabled, or Flash installed.  These are required in order to watch a YouTube video. <br />  To enable javascript using Internet Explorer:<ul><li>1. Select the 'Tools' option from your browser's tool bar<li>2. Select 'Internet Options' from the Tools menu<li>3. Click the <b>Advanced\</b> tab on this next popup <li>4. Scroll down and locate the 'Use Java' option and ensure that it is checked.\</ul>If you are using a different type of browser, you may need to read through the help documentation to determine how to set your Java settings. <br/>To install the latest version of flash, it is available for free at <p>http://www.macromedia.com/software/flashplayer/ ");
FAQ_topic[0][5] = new FAQ_item("Sound out of synch", "Out-of-sync sound is usually the result of using an audio codec that our system doesn't support.", "We're always adding new ones, but if this happens to your video, you can try re-encoding it with a different audio compression--we recommend MP3 audio for best results.");
   
   
FAQ_topic[1][0] = "Upload Issues";
FAQ_topic[1][1] = new FAQ_item("Can't confirm email address", "All of the videos on YouTube are streamed through a Flash player, so you need", "Before you can upload a video to YouTube for the first time, you'll need to confirm your email address. When you click the <b>Upload link\</b>, you will be shown a form with the email address you signed up with. If you like, you may change or correct it, then click the <b>Send Email\</b> button. <p> If you do not receive the confirmaiton email, check to see if it was sorted as spam. Some email providers cause a delay and it can take a few hours for the confirmation email to arrive. You may notice the <b>Welcome email\</b> you received from YouTube when you created your account, this is NOT the confirmation email.<p>If after several hours you still have not received the confirmation email in your inbox or spam folder and wish to have it sent again (or perhaps sent to another email address) you can have another email sent by returning to the file upload page.<p>Once you find and open the email, if you can't click the confirmation link, copy and paste ALL of the link and paste it in your web browser. ");
FAQ_topic[1][2] = new FAQ_item("Invalid file format error message", "The file type of your video doesn't appear to be supported by youtube.com. ", "YouTube does not currently accept videos in Flash (.flv) or RealAudio (.rm) format. You may need to try using software other than the software that came with your camera, such as Windows MovieMaker (included with every Windows installation), or Apple iMovie. Using these programs, you can easily edit your videos, add soundtracks, and change the file type, etc. Saving your video file as .avi, .mpg or .mov should enable to upload your video with no problems.");
FAQ_topic[1][3] = new FAQ_item("Upload never completes", "If you felt as though the upload was taking too long and as a result you cancelled the upload process,", "please keep in mind that uploading a video can take several hours, so please try again.If your video is taking longer than 24 hours to complete its upload, we recommend canceling the upload altogether and starting again.  ");

FAQ_topic[2][0] = "Login Issues";
FAQ_topic[2][1] = new FAQ_item("Can't remember username or password", "If you forgot your password, please click on this link to retrieve it: ", "http://www.youtube.com/forgot<br /><br /> If you forgot your username, please click on this link to retrieve it: http://www.youtube.com/forgot_username");
FAQ_topic[2][2] = new FAQ_item("Unable to login with correct information", "If you are unable to login with what you know to be your correct username and password, ", "please ensure that you have cookies turned on. If you aren't sure what cookies are, you can check your cookies a couple of different ways depending on the browser you are using.  Most likely you are using Internet Explorer.  Here are the steps for IE:<ul><li>Go to the Tools menu<li>Choose Internet Options<li>Select the Privacy Tab<li>Choose Advanced<li>First and Third Party Cookies should be set to 'Accept'\</ul>If they are not set to accept, please change this setting and try it again. <br />Also, if you are using a different type of browser, you may need to read through their help documentation to determine how to change your cookie settings.");

   

FAQ_topic[3][0] = " General 'How To' Question";
FAQ_topic[3][1] = new FAQ_item("How do I remove a comment?", "You have the ability to remove comments from your own videos.  To do this:", "<ul><li>1. Watch the video with the comment you would like to remove.  <li>2. Scroll down to the comment section and locate the comment that you would like to remove. <li>3. Click the 'remove comment' button beneath the comment. \</ul>You can also change how comments are posted to each of you videos. If you edit the properties for each video, you have the option to block all comments, require approval for each comment, or block all comments.");
FAQ_topic[3][2] = new FAQ_item("How do I block another user?", "You can block users from making comments on your videos or sending you messages. ", "To block a user, go to their Profile and click the 'Block User' button in the 'Connect with' box.");
FAQ_topic[3][3] = new FAQ_item("How do I make a movie?", "Please see our  guide on Making and Optimizing Your Videos for YouTube: ", "http://www.youtube.com/t/howto_makevideo");
FAQ_topic[3][4] = new FAQ_item("How do I share a private video?", "In order for someone to see a private video you have created you must share the video with them", "by using the share functionality on our site. You can do this by clicking the 'share video' button underneath the video or by specifying who you would like to share the video with as part of the upload process.  The person you share the video with must be a registered with YouTube in order to see the video.");
FAQ_topic[3][5] = new FAQ_item("How do I add a video to a webpage?", "To embed a video into any webpage go to the video you would like to embed, find", "the section labeled 'embed'. To the right of this is the code you will need to copy and paste into the webpage where you would like it to be embedded.");
FAQ_topic[3][6] = new FAQ_item("How do I cancel my account?", "To cancel your account, go to <a href='/my_account'>My Account\</a>, then click the Close Account link.", "Fill in your reason for closing the account and your password, then click 'Close My Account' and log out.");

</script>
<div id="subnavContent">
<table width="875" border="0">
<tr>
<td colspan="2">

	<ul id="subnavSidebar">
		<li class="navItem
"><a href="/t/about">About YouTube</a></li>
		<li class="navItem
"><a href="/press_room">Press Room</a></li>
		<li class="navItem
"><a href="/advertise">Advertising</a></li>
		<li class="navItemHighlight
"><a href="/contact">Contact Us</a></li>
		<li class="navItem
"><a href="http://www.pcrecruiter.net/pcrbin/regmenu.exe?uid=youtube.youtube">Jobs at YouTube</a></li>
		<li class="navItem
"><a href="/t/help_center">Help Center</a></li>
	</ul>


	<h1>Contact Us</h1>
	
	<p>YouTube is located in sunny San Bruno, California, USA.</p>

	<p>If you are the copyright owner of a video and feel it has been uploaded without your permission, please follow <a href="/t/dmca_policy">these directions</a> to submit a copyright infringement notice. If you have questions about our copyright infringement policy, you may also contact us using the form below.</p>

	<p>If you're a major producer or have professionally-produced videos you'd like to share, check out our <a href="/premium_register">Director Account</a> program.</p>
		
	<p>If this is a product or technical question, please provide as much detail as possible.</p>
		
	<ul>
		<li>What is your YouTube username?</li>
		<li>What exactly were you trying to do? (provide full URL)</li>
		<li>What is the exact page you received? (copy and paste any error messages)</li>
	</ul>
		
</td>
</tr>
<tr>
<td valign="top" width="400" align="left">
	<form name="contactForm" method="post" action="/contact">
	<table class="dataEntryTable" cellpadding="3" style="width:400px">
		<tr>
			<td class="formLabel">Your Email:</td>
			<td class="formField"><input type="text" size="30" maxlength="50" name="contact_email" value=""></td>
		</tr>
		<tr>
			<td class="formLabel">Subject:</td>
			<td class="formField">
				<select name="contact_subject" onChange="checkSubject();">
					<option value="0">---</option>
					<option value="1" >Customer Service</option>
					<option value="6" >Copyright Inquiry</option>
					<option value="2" >Business Inquiry</option>
<!--					<option value="3" >Marketing Inquiry</option> -->
					<option value="4" >Developer Question</option>
					<option value="5" >Press Inquiry</option>
					<option value="7" >Job Inquiry</option>
					<option value="9" >YouTube Colleges</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="formLabel"><div id="colleges_label" name="colleges_label" style="display: none">Topic:</div></td>
			<td class="formField">
				<div id="colleges_dropdown" name="colleges_dropdown" style="display: none">
					<select name="colleges_subject" onChange="prePopMessage();">
						<option value="na">---</option>
						<option value="colleges_0">Add College Request</option>
						<option value="colleges_1">Suggestions/Feedback</option>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td class="formLabel"><div id="customer_service_label" name="customer_service_label" style="display: none">Topic:</div></td>
			<td class="formField">
				<div id="customer_service_dropdown" name="customer_service_dropdown" style="display: none">
				<select name="customer_service_subject" onChange="hideAllFAQ();toggleDisplay(document.contactForm.customer_service_subject.value);">
					<option value="na">---</option>
					<option value="customer_service_0">Video Player Issue</option>
					<option value="customer_service_1">Upload Video Issue</option>
					<option value="customer_service_2">Login Issues</option>
					<option value="customer_service_3">General "How To" Questions</option>
					<option value="customer_service_4">Other Site Issues</option>
					<option value="customer_service_5">Suggestions/Feedback</option>					
				</select>
				</div>
			</td>
		</tr>
		<tr valign="top"> 
			<td class="formLabel">Message:</td>
			<td class="formField"><textarea name="contact_message" cols="40" rows="10" class="standardText"></textarea></td>
		</tr>
		<tr>
			<td class="formLabel">&nbsp;</td>
			<td class="formField"><input type="submit" name="submit" value="Send Message" onclick="document.contactForm.submit();this.disabled=true;" /></td>
		</tr>
	</table>
	</form>

	<!--End Form Section-->
</td>
<td valign="top" width="475">


<!--Begin FAQ-->
<script language="javascript" type="text/javascript">
for (i=0;i<FAQ_topic.length;i++) {
	document.write("<div id='customer_service_" + i  + "' style='display:none;border: 2px solid #CCCCCC'><div class='headerBox'><b>");
	document.write(FAQ_topic[i][0] + "</b></div>");
	for (j=1;j<FAQ_topic[i].length;j++) {
		document.write("<div class='contentBox'>");
		document.write("<div class='question_" + i + j  + "'>");
		document.write("<b>" + FAQ_topic[i][j].question + "</b></div>");
		document.write("<div id='answer_" + i + j  + "' class='answerBox'>");
		document.write("<span id='begin_A_" + i + j  + "'>"); 
		document.write("<blockquote>");
		document.write(FAQ_topic[i][j].answerBegin);
		document.write("</span>");
		document.write("<span id='moreLink_A_" + i + j  + "' class='smallText'> ... (<a href='#' class='eLink' onclick=\"showInline('more_A_" + i + j  + "'); hideInline('moreLink_A_" + i + j  + "'); showInline('begin_A_" + i + j  + "'); return false;\">more</a>)</span>");				
		document.write("<span id='more_A_" + i + j  + "' style='display: none'> ");
		document.write(FAQ_topic[i][j].answerMore);
		document.write("</blockquote></span></div></div>");
		
	}
	document.write("</div>" );
}


//function to hide all FAQ divs

function hideAllFAQ() {
	document.getElementById("customer_service_0").style.display="none";       
 	document.getElementById("customer_service_1").style.display="none";       
 	document.getElementById("customer_service_2").style.display="none";       
 	document.getElementById("customer_service_3").style.display="none";       
}
</script>
<!--End FAQ-->
<div id="customer_service_4"></div>
<div id="customer_service_5"></div>
	</td>
	</tr>
</table>	
</div>
<?php
require "needed/end.php";
?>