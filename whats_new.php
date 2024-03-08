<?php
require "needed/start.php";
if($session['staff'] == 1 && isset($_POST['field_blog'])) {
  // Prepare the SQL query to insert a new blog post
  $sql = "INSERT INTO blog (title, content, author, id) VALUES (:title, :content, :author, :id)";

  // Bind the parameters to the prepared statement
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":title", $_POST['field_blog']);
  $stmt->bindParam(":content", $_POST['field_blog_content']);
  $stmt->bindParam(":author", $session['username']);
  $stmt->bindParam(":id", generateId());
  
  // Execute the prepared statement
  try {
    $stmt->execute();
    $posted = 1;
  } catch (PDOException $e) {
    alert("Was unable to blog.", "error");
  }
}

$stmt = $conn->query("SELECT * FROM blog ORDER BY posted DESC");

?>

<?php if ($posted == 1) {
    alert("You've just blogged!");
}
?>

<div class="pageTable">
<?php
	// Loop through the results and display each post
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$title = $row['title'];
        $content = $row['content'];
        $date = $row['posted'];
        $author = $row['author'];
	?>
   <div class="tableSubTitle"><?php echo retroDate($date); ?></div>
<div style="padding: 0px 15px 30px 15px;">
<?php echo $content; ?>
</div> 
<? } ?>
<?php if($session['staff'] == 1) { ?>
   <div class="formTitle">What's Popping?</div>
   <div class="pageTable">
    <table width="100%" cellpadding="5" cellspacing="0" border="0">
	<form method="post">
	<tbody>
    <tr>
		<td width="200" align="right"><span class="label">Title</span> (will not be published)</td>
		<td><input type="text" size="30" maxlength="60" name="field_blog" placeholder="What's popping?"></td>
	</tr>
	<tr>
		<td align="right" valign="top"><span class="label">Content:</span></td>
		<td><textarea name="field_blog_content" cols="40" rows="4" placeholder="Come on..."></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Post Blog"></td>
	</tr>
    </form>
</tbody></table>

</div>
    </div>
  <?php } ?>			
</div>
<?php
require "needed/end.php";
?>