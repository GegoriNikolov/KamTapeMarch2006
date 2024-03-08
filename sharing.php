<?php
require "needed/start.php";
?>

<div class="highlight">
<br />
<ul>
	<li><a href="#link_single" style="font-size: 13px; font-weight: bold;">How do I link to a single KamTape video?</a>
	<br>
	<br>
	<li><a href="#link_list" style="font-size: 13px; font-weight: bold;">How do I link to a list of KamTape videos?</a>
</ul>
</div>
<br />
<br />
<div class="tableSubTitle"><a name="link_single"></a>How to link to a single KamTape video</div>

<span class="highlight">First, go to the video that you want to share. Look for the box under the video player.</span> <br>
	<br>
<center>
  <img src="img/2OptionsHowTo.jpg" border="1" />
</center><br>
<span class="highlight">Here you will see two ways to share your video:</span> <br>
1. <span class="highlight">Video URL</span>: This URL leads directly to the KamTape page that plays the video. You can copy and paste the URL and in E-mail and send it to email friends. The URL looks similar to this:<p><center>http://www.kamtape.com/?v=Xuz87lEvdC5</center>
<br>2. <span class="highlight">Video Player</span>: Copy and paste the HTML snippet into your website to embed the KamTape player directly into your website. Your website will be able to play the videos without leaving your website. It will appear like this but they can alter the size of the video by changing the width.
<table width="760" border="0" cellspacing="0" cellpadding="0">
    <tbody><tr>
      <td width="380" valign="middle">
        <div align="left"><iframe id="embedplayer" src="http://www.kamtape.com/v/Xuz87lEvdC5" width="448" height="382" allowfullscreen scrolling="off" frameborder="0"></iframe></div></td>
      <td width="400" valign="middle">
        <div align="left">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="contentBreaks">
            <tbody><tr>
              <td>                
			  <div align="center">
              <tr>
              <span class="highlight"><center>Post a video blog on...</center></span><br><img src="img/BloggerLogo.gif" align="absmiddle" width="277" height="101">
              </tr>
              </div>
              </td>
            </tr>
          </tbody></table>
          <br>
          <br>
      </div></td>
    </tr>
  </tbody></table>
<br><br><div class="tableSubTitle">How do I link to a list of KamTape videos?</div>

<span class="standoutLabel">
<span class="highlight">Want to show off your own KamTape videos, or specifically tagged videos, on your website? We have some great ways to do that!</span> <br>
</span>
<br />
<br />
<br />
1. By placing a small snippet of HTML code in your webpage, you can pull up a list of all your KamTape videos in a neat, little window. Take a look at the example below; on the left is the HTML snippet that you would copy+paste into your webpage.
Remember to replace the part of the code that says &quot;YOUR_USERNAME&quot; with your KamTape username. 
 As a result, a small box with your videos will be rendered as shown on the right.

	<br>
	<br>

	<table width="790">
	<tr>
		<td valign="top" align="left">
			<br />
			<span class="highlight">HTML snippet for FriendProject</span>
			<br/>

			<textarea cols="55" rows="6" id="snippet_embed" wrap="soft"><embed src="http://www.KamTape.com/swf_show/YOUR_USERNAME" quality="high" width="425" height="350" name="myclips" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></textarea>

			<br/>
			<br/>
			<br/>
			<span class="highlight">HTML snippet for all other sites</span>
			<br/>

			<textarea cols="55" rows="6" id="snippet_iframe" wrap="soft"><iframe id="videos_list" name="videos_list" src="http://www.KamTape.com/videos_list.php?user=YOUR_USERNAME" scrolling="auto" width="265" height="300" frameborder="0" marginheight="0" marginwidth="0"></iframe></textarea>

	  <br/>      </td>
		<td valign="top" align="left">
			<br />
			<span class="highlight">What Shows Up</span>
			<br/>
			<iframe id="videos_list" name="videos_list" src="videos_list_sample.html" scrolling="auto" width="265" height="300" frameborder="0" marginheight="0" marginwidth="0">
	  </iframe>	  </td>
	</tr>
</table>

	<br/>
	<br/>
	<br/>
	<br/>

2. You can also create links to your profile, your videos, or specifically tagged videos. 

 Remember to replace the part of the link that says YOUR_USERNAME with your KamTape username. 
  
 To pull up results for a tag search, simply replace the part of the link that says YOUR_QUERY with a tag term of your choice. For example, if you wanted to pull up results for the tag term wedding the link would be: <a href="http://www.KamTape.com/tags/wedding">http://www.KamTape.com/tags/wedding</a></span>
    <br/>
	<br/>

	<table width="790" border="1" cellpadding="5" cellspacing="0">
	<tbody><tr bgcolor="#FFFFCC">
		<th valign="top" align="center">Purpose</th>
		<th valign="top" align="center">Link</th>
	</tr>
	<tr>
		<td valign="top" align="left">To link to your profile page</td>
		<td valign="top" align="left">http://www.kamtape.com/user/YOUR_USERNAME</td>
	</tr>
	<tr>
		<td valign="top" align="left">To link to your videos page</td>
		<td valign="top" align="left">http://www.kamtape.com/videos/YOUR_USERNAME</td>
	</tr>
	<tr>
		<td valign="top" align="left">To pull up results for a tag search</td>
		<td valign="top" align="left">http://www.kamtape.com/tags/YOUR_QUERY</td>
	</tr>
</tbody></table>

	<br/>
	<br/>
	<br/>
	<br/>

3. You can even put an HTML snippet on your website that automatically pulls up the latest videos with a certain tag term. Let's say you want to show all videos related to Surfing on your website. In addition, you want your website to be automatically updated whenever a new video is uploaded with the tag term Surfing. By using the snippet below, you can do this! 

	<br/>
	<br/>

	<table width="790">
		<tr>
		<td valign="top">
	
		<span class="highlight">HTML snippet</span>
			<br/>

		<textarea cols="55" rows="6" id="snippet_embed" wrap="soft"><iframe id="videos_list" name="videos_list" src="http://www.KamTape.com/videos_list.php?tag=Surfing" scrolling="auto" width="265" height="400" frameborder="0" marginheight="0" marginwidth="0"></iframe></textarea>

			<br/>
			<br/>
		

		<span class="standoutLabel">Tip:</span> You can replace the word Surfing above to a tag term of your choice. </td>
		<td valign="top" align="left">
			<span class="highlight">What Shows Up</span>
			<br/>

		<iframe id="videos_list" name="videos_list" src="http://www.KamTape.com/videos_list.php?tag=Surfing" scrolling="auto" width="265" height="400" frameborder="0" marginheight="0" marginwidth="0"></iframe>		</td>
		</tr>
	</table>


		</div>
		</td>
	</tr>
</table>
<?php
require "needed/end.php";
?>