<?php
require "needed/start.php";

if(empty($_SESSION)) {
	header("Location: index.php");
}
$msg = $conn->prepare("SELECT * FROM messages LEFT JOIN users ON users.uid = messages.sender WHERE pmid = ?");
$msg->execute([$_GET['id']]);

if($msg->rowCount() == 0) {
	header("Location: /my_messages.php");
	die();
} else {
	$msg = $msg->fetch(PDO::FETCH_ASSOC);
}
if ($msg['receiver'] != $session['uid']) {
   header("Location: my_messages.php");
}
$read_detect = $conn->prepare("UPDATE messages SET isRead = 1 WHERE pmid = ?");
	$read_detect->execute([
		trim($msg['pmid'])
	]);
?>

<div class="tableSubtitle">Inbox Messages</div>
<table width="70%" align="center" cellpadding="5" cellspacing="0" border="0">
         <tr align="center">
		 <td align="center" colspan="3">
                <a href="/my_messages.php">Inbox Messages</a> | <a href="/outbox.php">Sent Messages</a>
            </td></tr>
            </table>
<table width="100%" align="center" cellpadding="1" cellspacing="1" border="0" bgcolor="#CCCCCC">
	<tbody>
	<tr>
		
		<td width="100%"><img src="img/pixel.gif" width="1" height="5"> <a href="#"><< Prev</a>&nbsp;|&nbsp;<a href="#">Next >></a></td>
	</tr>
	<tr>
		
		<td>
				
	<table width="4px" cellpadding="4" cellspacing="9" table="table" align="left">
                <tbody><tr><td colspan="1" height="10" width="35" align="right"></td></tr>
                                <tr valign="top">
					<td align="right" valign="top"><span class="label">From:</span></td>
					<td><a href="/user/<?php echo htmlspecialchars($msg['username']);?>"><?php echo htmlspecialchars($msg['username']);?></a></td></tr>
                    <tr valign="top">
                </tr>
                <tr valign="top">
					<td align="right" valign="top"><span class="label">Sent:</span></td>
					<td><?php echo retroDate($msg['created'], "l, F j, Y"); ?></td>
                </tr>
                <tr valign="top">
					<td align="right" valign="top"><span class="label">Subject:</span></td>
					<td><?php echo decrypt($msg['subject']); ?></td>
                </tr>
                <tr valign="top">
					<td align="right" valign="top"><span class="label">Message:</span></td>
					<td><div class="mailMessageArea"><?php echo decrypt($msg['body']); ?></div></td>
                    
                </tr>
                <? if(!empty($msg['attached'])) {
                    $nonexistent = "true";
                    $video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
                    $video->execute([$msg['attached']]);

                    if($video->rowCount() > 0) {
	                $video = $video->fetch(PDO::FETCH_ASSOC);
                    $nonexistent = "false";
                    }
                    if($nonexistent == "false") {
                    ?>
                	<td align="right" valign="top"></td>
					<td><a href="/?v=<?php echo htmlspecialchars($video['vid']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>" width="120" height="90" class="moduleFeaturedThumb"></a></td>
                <? } }?>
                <tr valign="top">
					<td align="right" valign="top"></td>
					<td><form method="get" action="f_message.php" onsubmit="return confirm('Please confirm you know that this electronic message will be deleted and unrecoverable when you press OK.');">
											<input type="hidden" value="<?php echo $msg['pmid']; ?>" name="msg">
											<input type="submit" value="Delete">
										</form></td>
                    
                </tr>
                                                </tbody></table>
		
		</td>
		<td><img src="img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		
		<td><img src="img/pixel.gif" width="1" height="5"></td>
		
	</tr>
</tbody></table>

<?php
require "needed/end.php";
?>