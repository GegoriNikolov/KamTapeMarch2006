<?php 
require "needed/scripts.php";

use SoftCreatR\MimeDetector\MimeDetector;
use SoftCreatR\MimeDetector\MimeDetectorException;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['fileToUpload'])) {
    $maxFileSize = 100 * 1024 * 1024;
    $fileSize = $_FILES['fileToUpload']['size'];
    
    if ($fileSize > $maxFileSize) {
         header("Location: my_videos_upload.php");
         exit;
    }
// create an instance of the MimeDetector
$mimeDetector = new MimeDetector();

// set our file to read
try {
    $mimeDetector->setFile($_FILES['fileToUpload']['tmp_name']);
} catch (MimeDetectorException $e) {
    header("Location: my_videos_upload.php");
         exit;
}

// try to determine its mime type and the correct file extension
$type = $mimeDetector->getFileType();

$mime = strtolower($type['mime']);
$isVideo = 0;

// Check if the MIME type matches the most popular video formats
if (strpos($mime, 'video/') === 0) {
    $ok = 1;
    if ($ok == 1) {
        $cooldown = $conn->prepare(
			"SELECT * FROM videos
			WHERE uid = ? AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY)
			ORDER BY uploaded DESC"
		);
        if ($session['username'] == "firefoxgamer") { $coolit = 1; } else { $coolit = 8; }
		if($cooldown->rowCount() > $coolit) {
			redirect("my_videos.php?whoops");
		}
        $my_videos = $conn->prepare(
			"SELECT * FROM videos
			WHERE uid = ?
			ORDER BY uploaded DESC"
		);
        $my_videos->execute([$session['uid']]);
		if($my_videos->rowCount() > 5) {
			$video_id = generateId();
		} else {
            $video_id = $session['uid'];
        }
        $field_upload_tags = trim($_POST['field_upload_tags']);
        $field_upload_tags = str_replace(',', '', $field_upload_tags); // Remove commas
        $field_upload_tags = str_replace('  ', ' ', $field_upload_tags); // Remove whitespaces
        $field_upload_tags = str_replace('#', '', $field_upload_tags); // Remove hashtags
         if ($_POST['field_upload_country'] == '---') {
             $_POST['field_upload_country'] = NULL;
         }
         if ($_POST['private'] == 2) {
             $privacy = 2;
         } else {
             $privacy = 1;
         }
		$stmt = $conn->prepare("INSERT INTO videos (uid, vid, tags, title, description, file, privacy, cdn, recorddate, address, addrcountry) VALUES (:uid, :vid, :tags, :title, :description, :file, :privacy, :cdn, :recorddate, :address, :country)");
$stmt->execute([
    ':uid' => $session['uid'],
    ':vid' => $video_id,
    ':tags' => $field_upload_tags,
    ':title' => strip_tags($_POST['field_upload_title']),
    ':description' => strip_tags($_POST['field_upload_description']),
    ':file' => strip_tags($_FILES['fileToUpload']['name']),
    ':privacy' => $privacy,
    ':cdn' => "14",
    ':recorddate' => isset($_POST['addr_date']) ? strip_tags($_POST['addr_date']) : null,
    ':address' => isset($_POST['field_upload_address']) ? strip_tags($_POST['field_upload_address']) : null,
    ':country' => isset($_POST['field_upload_country']) ? strip_tags($_POST['field_upload_country']) : null
]);

    // Create a new cURL resource
    $ch = curl_init();

    // Set the request URL
    // Determine the request URL based on the protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $endpoint = 'v14.kamtape.com/my_videos_upload_post.php';
    $url = $protocol . $endpoint;

    // Set the request URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Set the request method to POST
    curl_setopt($ch, CURLOPT_POST, true);

  // Set the request payload
    $file = new \CURLFile($_FILES['fileToUpload']['tmp_name'], $_FILES['fileToUpload']['type'], $_FILES['fileToUpload']['name']);

$postData = array_merge($_POST, array(
    'fileToUpload' => $file,
    'video_id' => $video_id
));

curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
    $error = curl_error($ch);
    // Handle the error
    header("Location: my_videos_upload.php");
    }

    // Close the cURL resource
    curl_close($ch);

    $successful = "/my_videos_upload_complete.php?v=" . $video_id;

    header("Location: $successful");
    exit();

    }
}
} else {
header("Location: my_videos_upload.php");
}


?>