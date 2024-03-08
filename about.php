<?php
require "needed/start.php";
?>
<div style="padding: 0px 5px 0px 5px;">
<div class="tableSubtitle">About Us</div>

<div class="pageTable">
<span class="highlight">What is KamTape?</span>

<br><br>
KamTape is a user-friendly video sharing service where you can easily share your favorite memories with friends and family. Join us today and start sharing your videos with ease.
<br>
KamTape is the way to get your videos to the people who matter to you. With KamTape you can:

<ul>
<li> Show off your favorite videos to the world
<li> Take videos of your dogs, cats, and other pets
<li> Blog the videos you take with your digital camera or cell phone
<li> Securely and privately show videos to your friends and family around the world
<li> ... and much, much more!
</ul>
<?php if(empty($_SESSION)) { ?>
<br><span class="highlight"><a href="signup.php">Sign up now</a> and open a free account.</span>
<br><br> <?php } ?><br>

To learn more about our service, please see our <a href="help.php">Help</a> section.<br><br>

Please feel free to <a href="contact.php">contact us</a>.<br><br><br>
<span class="highlight">What <i>the hell</i> is KamTape?</span>

<br><br>

Founded in July 2005 by <a href="/user/jr" class="bold">jr</a>, KamTape is a project to recreate old YouTube with the most attention to detail possible, with also a focus on community. Nearly every single page of KamTape is recreated meticiously according to old images, videos, blogs, websites, and HTML archives. It will feel just like you're back in 2005 scrolling videos on YouTube. Aside from this, KamTape also has a huge focus on community. We hold monthly contests and moderators frequently interact with users. So why not join?
<br><br><span class="highlight">Credits</span>

<br><br>

Cool people who've worked on the site.

<br><br>
<ul>
<li><strong><a href="/user/jr">jr</a></strong> -- Current owner and main developer of the website. He's the reason for all of the cool stuff you see here. (I sacrifice my sanity for this hobby.)</li>
<li><strong><a href="/user/BoredWithADHD">BoredWithADHD</a></strong> -- This dude creates most of the logos for KamTape and originally envisioned the idea of the logo being a video camera -- also sort of inspired the name. His logos are accurate and beautiful.</li>
<li><strong><a href="/user/idsniper">idsniper</a></strong> -- Responsible for the current Flash player.</li>
<li><strong><a href="/user/purpleblaze">purpleblaze</a></strong> -- Recreates the Flash player with immense accuracy for unfortunate souls who do not have Flash.</li>
</ul>
</div>

<?php
require "needed/end.php";
?>