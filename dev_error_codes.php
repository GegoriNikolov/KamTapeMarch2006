<?php
require "needed/start.php";
?>
<h1>Developer API &#8211; Error Codes</h1>

<dl>
	<dt>1 : YouTube Internal Error</dt>
	<dd>This is a potential issue with the YouTube API. Please <a href="/contact">report the issue to us</a> using the subject "Developer Question."</dd>

	<dt>2 : Bad XML-RPC format parameter</dt>
	<dd>The parameter passed to the XML-RPC API call was of an incorrect type.  Please see the <a href="/dev_xmlrpc">XML-RPC interface documentation</a> for more details.</dd>

	<dt>3 : Unknown parameter specified</dt>
	<dd>Please double-check that the specified parameters match those in the API reference.</dd>

	<dt>4 : Missing required parameter</dt>
	<dd>Please double-check that all required parameters for the API method you're calling are present in your request.</dd>

	<dt>5 : No method specified</dt>
	<dd>All API calls must specify a method name.</dd>

	<dt>6 : Unknown method specified</dt>
	<dd>Please check that you've spelled the method name correctly.</dd>

	<dt>7 : Missing dev_id parameter</dt>
	<dd>All requests must have a developer ID.  If you don't have one, please create a <a href="/my_profile_dev">developer profile</a>.</dd>

	<dt>8 : Bad or unknown dev_id specified</dt>
	<dd>All requests must have a valid developer ID.  If you don't have one, please create a <a href="/my_profile_dev">developer profile</a>.</dd>
</dl>

<?php 
require "needed/end.php";
?>