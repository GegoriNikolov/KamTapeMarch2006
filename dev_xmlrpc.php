<?php
require "needed/start.php";
?>
<h1>Developer API &#8211; XML-RPC Interface</h1>

<p>The XML-RPC interface is conceptually very similar to the REST interface.  To make an XML-RPC request, you'll need to submit POST requests to the YouTube XML-RPC endpoint:</p>

<div class="codeBox"><code>
	http://www.youtube.com/api2_xmlrpc</code>
</div>

<p>Your XML-RPC request should have exactly one XML-RPC parameter of type "struct" that contains a field for every YouTube API parameter.  The only exception is the method name, which goes in the standard XML-RPC place (methodName).  Here's the equivalent XML-RPC call for the example given in the "Getting Started" section of the <a href="/dev_intro">API introduction page</a>.</p>

<div class="codeBox"><code>
&lt;?xml version='1.0'?&gt;<br>
&lt;methodCall&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;methodName&gt;youtube.users.get_profile&lt;/methodName&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;params&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;param&gt;&lt;value&gt;&lt;struct&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;member&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;<b>dev_id</b>&lt;/name&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;value&gt;&lt;string&gt;<b>YOUR_DEV_ID</b>&lt;/string&gt;&lt;/value&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/member&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;member&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;name&gt;<b>user</b>&lt;/name&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;value&gt;&lt;string&gt;<b>YOUTUBE_USER_NAME</b>&lt;/string&gt;&lt;/value&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/member&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/struct&gt;&lt;/value&gt;&lt;/param&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/params&gt;<br>
&lt;/methodCall&gt;</code>
</div> <!-- XML-RPC request example -->

<p>The XML-RPC response is a string that contains an escaped standalone XML document that contains the results of the API call.  You may pass the XML document to your preferred XML parser to get parse the results.  Here's an example XML-RPC result:</p>

<div class="codeBox"><code>
&lt;?xml version='1.0'?&gt;<br>
&lt;methodResponse&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;params&gt;&lt;param&gt;&lt;value&gt;&lt;string&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot;?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;ut_response status=&quot;ok&quot;&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;user_profile&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;first_name&gt;YouTube&lt;/first_name&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;last_name&gt;User&lt;/last_name&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/user_profile&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/ut_response&gt;</b><br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;/string&gt;&lt;/value&gt;&lt;/param&gt;&lt;/params&gt;<br>
&lt;/methodResponse&gt;</code>
</div>
<!-- XML-RPC response example -->

<p>Unescaping the XML response document will yield the same result as the response to a REST call.  For ease-of-implementation, we highly recommend that you use an XML-RPC client library to handle the details of XML-RPC calls.</p>

<?php 
require "needed/end.php";
?>