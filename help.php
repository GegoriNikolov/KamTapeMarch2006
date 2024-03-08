<?php
require "needed/start.php";
if($session['staff'] == 1 && isset($_POST['field_qa'])) {
  // Prepare the SQL query to insert a new blog post
  $sql = "INSERT INTO questions_and_answers (question, answer) VALUES (:question, :answer)";

  // Bind the parameters to the prepared statement
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":question", $_POST['field_qa']);
  $stmt->bindParam(":answer", $_POST['field_qa_answer']);
  
  // Execute the prepared statement
  try {
    $stmt->execute();
    $posted = 1;
  } catch (PDOException $e) {
    alert("Failed to submit.", "error");
    exit;
  }
}

$stmt = $conn->query("SELECT * FROM questions_and_answers ORDER BY id ASC");
$rowCount = $stmt->rowCount(); // Get the number of rows

?>

<div class="tableSubtitle">Help</div>
<?php if ($posted == 1) {
    alert("You have just answeed a question!");
}
?>

<div class="pageTable">
<?php
	// Loop through the results and display each post
	$currentRow = 1; // Variable to keep track of the current row
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$question = $row['question'];
        $answer = $row['answer'];
	?>
    <span class="highlight">Q: <?php echo $question; ?></span>

<br><br>A: <?php echo $answer; ?> 
<?php if ($currentRow !== $rowCount) { ?> <!-- Check if it's the last row -->
<br/>
<br/>
	<?php }
	$currentRow++; // Increment the current row counter
	?>
    <?php }
	?>

<br><br><br><span class="highlight">Contact KamTape</span>
<br><br>If you have any account or video issues, please contact us <a href="contact.php">here</a>.
Also, if you have any ideas or suggestions to make our service better, please don't hesitate to drop us a line.
<?php if($session['staff'] == 1) { ?>
   <br><br><br>
   <div class="pageTable">
    <table width="100%" cellpadding="5" cellspacing="0" border="0">
	<form method="post">
	<tbody>
    <td width="200" align="right"><span class="highlight">Help Answer The Community!</span></td>
    <tr>
		<td width="200" align="right"><span class="label">Q:</span></td>
		<td><input type="text" size="30" maxlength="350" name="field_qa" placeholder="How long can my video be?"></td>
	</tr>
	<tr>
		<td align="right" valign="top"><span class="label">A:</span></td>
		<td><textarea name="field_qa_answer" cols="40" rows="4" placeholder="There is no time limit on your video, but the video file you upload must be less than 100 MB in size."></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Answer Now"></td>
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
