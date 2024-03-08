<?php
require "needed/start.php";
?>
	<link href="/css/studio.css" type="text/css" rel="stylesheet">

<div id="baseContainer">
	<h1>API Documentation</h1>
	
				<ul>
					<li><a href="/dev_intro">Introduction</a></li>
				</ul>
		
				<h3>API Call Interfaces</h3>
				<ul>
					<li><a href="/dev_rest">REST Interface</a></li>
					<li><a href="/dev_xmlrpc">XML-RPC Interface</a></li>
				</ul>
		
				<h3>Error Codes</h3>
				<ul>
					<li><a href="/dev_error_codes">List of error codes</a></li>
				</ul>
					<h3>API Functions</h3>
		
					<h4>User Information</h4>
					<ul>
						<li><a href="/dev_api_ref?m=youtube.users.get_profile">youtube.users.get_profile</a></li>
						<li><a href="/dev_api_ref?m=youtube.users.list_favorite_videos">youtube.users.list_favorite_videos</a></li>
						<li><a href="/dev_api_ref?m=youtube.users.list_friends">youtube.users.list_friends</a></li>
					</ul>
		
					<h4>Video Viewing</h4>	
					<ul>
						<li><a href="/dev_api_ref?m=youtube.videos.get_details">youtube.videos.get_details</a></li>
						<li><a href="/dev_api_ref?m=youtube.videos.list_by_tag">youtube.videos.list_by_tag</a> (now with paging)</li>
						<li><a href="/dev_api_ref?m=youtube.videos.list_by_user">youtube.videos.list_by_user</a></li>
						<li><a href="/dev_api_ref?m=youtube.videos.list_featured">youtube.videos.list_featured</a></li>
					</ul>
					
					
	
	
</div>
<?php 
require "needed/end.php";
?>