<?php
require "needed/scripts.php";
header('Content-type: text/xml');
if (!isset($_GET['video_id'])) {
	echo '<?xml version="1.0" encoding="utf-8"?>
<ut_response status="fail"><error><code>4</code><description>Missing required parameter.</description></error></ut_response>';
	die();
}
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$_GET['video_id']]);
$video = $video->fetch(PDO::FETCH_ASSOC);
$search = str_replace(" ", "|", $video['tags']);
$relatedvideos = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(description), LOWER(?)) > 0) DESC LIMIT 20");
$relatedvideos->execute([$search, $search, $search, $search, $search]);

echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<ut_response status="ok">
<video_list>
<?php foreach($relatedvideos as $relatedvideo) { $relatedvideo['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?"); $relatedvideo['views']->execute([$relatedvideo['vid']]); $relatedvideo['views'] = $relatedvideo['views']->fetchColumn(); ?>
    <video>
      <title><?php echo htmlspecialchars($relatedvideo['title']); ?></title>
      <run_time><?php echo gmdate("i:s", $relatedvideo['time']); ?></run_time>
      <view_count><?php echo $relatedvideo['views']; ?></view_count>
	  <author><?php echo htmlspecialchars($relatedvideo['username']); ?></author>
	  <rating><? echo htmlspecialchars(getRatingAverage($relatedvideo['vid'])); ?></rating>
      <url>/watch?v=<?php echo htmlspecialchars($relatedvideo['vid']); ?></url>
     <thumbnail_url>/get_still.php?video_id=<?php echo htmlspecialchars($relatedvideo['vid']); ?></thumbnail_url>
     <thumbnail_url2>/get_still.php?video_id=<?php echo htmlspecialchars($relatedvideo['vid']); ?></thumbnail_url2>
    </video>
<? } ?>
  </video_list>
</ut_response>