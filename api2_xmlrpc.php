<?php
require "needed/scripts.php";
$methodCall = new SimpleXMLElement(file_get_contents('php://input'));
$methodName = (string) $methodCall->methodName;
header('Content-type: text/xml');
echo "<?xml version='1.0'?>
<methodResponse>
    <params><param><value><string>";
echo '<?xml version="1.0" encoding="utf-8"?>';
$ending = '    </string></value></param></params>
</methodResponse>';
$missingparam = '<ut_response status="fail"><error><code>4</code><description>Missing required parameter.</description></error></ut_response>';
if ($methodName == "youtube.videos.list_featured" || $methodName == "youtube.users.list_favorite_videos" || $methodName == "youtube.videos.list_by_tag" || $methodName == "youtube.videos.list_by_user") {
switch($methodName) {
case "youtube.videos.list_featured":
$featured = $conn->query(
"SELECT * FROM picks 
LEFT JOIN videos ON videos.vid = picks.video
LEFT JOIN users ON users.uid = videos.uid
WHERE (videos.converted = 1 AND videos.privacy = 1) GROUP BY picks.video
ORDER BY picks.featured DESC LIMIT 25"
);
break;
case "youtube.users.list_favorite_videos":
foreach ($methodCall->params->param->value->struct->member as $xmlmember) {
	if ((string) $xmlmember->name == 'user') {
		$xmluser = (string) $xmlmember->value->string;
	}
}
if(!isset($xmluser)) {
echo $missingparam;
echo $ending;
die();
}
$profile = $conn->prepare("SELECT * FROM users WHERE users.username = ?");
$profile->execute([$xmluser]);

if($profile->rowCount() == 0) {
echo '<ut_response status="fail"><error><code>101</code><description>No user was found with the specified username.</description></error></ut_response>';
echo $ending;
die();
} else {
$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$featured = $conn->prepare(
"SELECT * FROM favorites
LEFT JOIN videos ON favorites.vid = videos.vid
LEFT JOIN users ON users.uid = videos.uid
WHERE favorites.uid = ?
ORDER BY favorites.fid DESC LIMIT 10"
);
$featured->execute([$profile['uid']]);
break;
case "youtube.videos.list_by_tag":
foreach ($methodCall->params->param->value->struct->member as $xmlmember) {
	if ((string) $xmlmember->name == 'tag') {
		$xmltag = (string) $xmlmember->value->string;
	}
	if ((string) $xmlmember->name == 'page') {
		$xmlpage = (string) $xmlmember->value->string;
	}
	if ((string) $xmlmember->name == 'per_page') {
		$xmlper_page = (string) $xmlmember->value->string;
	}
}
$page = isset($xmlpage) ? intval($xmlpage) : 1;
$ppv = isset($xmlper_page) ? intval($xmlper_page) : 20;
$offs = ($page - 1) * $ppv;
if(!isset($xmltag)) {
echo $missingparam;
echo $ending;
die();
}
$search = str_replace(" ", "|", $xmltag);
$featured = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(tags), LOWER(?)) > 0) DESC LIMIT $ppv OFFSET $offs");
$featured->execute([$search, $search, $search, $search, $search]);
break;
case "youtube.videos.list_by_user":
foreach ($methodCall->params->param->value->struct->member as $xmlmember) {
	if ((string) $xmlmember->name == 'user') {
		$xmluser = (string) $xmlmember->value->string;
	}
}
if(!isset($xmluser)) {
echo $missingparam;
echo $ending;
die();
}
$profile = $conn->prepare("SELECT * FROM users WHERE users.username = ?");
$profile->execute([$xmluser]);

if($profile->rowCount() == 0) {
echo '<ut_response status="fail"><error><code>101</code><description>No user was found with the specified username.</description></error></ut_response>';
echo $ending;
die();
} else {
$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$featured = $conn->prepare(
"SELECT * FROM videos
LEFT JOIN users ON users.uid = videos.uid
WHERE videos.uid = ? AND videos.converted = 1 AND videos.privacy = 1
ORDER BY videos.uploaded DESC"
);
$featured->execute([$profile['uid']]);
break;
}
?>

<ut_response status="ok">
    <video_list>
<?php foreach($featured as $pick) { 
$pick['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
$pick['views']->execute([$pick['vid']]);
$pick['views'] = $pick['views']->fetchColumn();
	
$pick['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
$pick['comments']->execute([$pick['vid']]);
$pick['comments'] = $pick['comments']->fetchColumn();
?>
        <video>
            <author><?php echo htmlspecialchars($pick['username']); ?></author>
            <id><?php echo htmlspecialchars($pick['vid']); ?></id>
            <title><?php echo htmlspecialchars($pick['title']); ?></title>
            <length_seconds><?php echo htmlspecialchars($pick['time']); ?></length_seconds>
            <rating_avg><? echo htmlspecialchars(getRatingAverage($pick['vid'])); ?></rating_avg>
            <rating_count><? echo htmlspecialchars(getRatingCount($pick['vid'])); ?></rating_count>
            <description><?php echo htmlspecialchars($pick['description']); ?></description>
            <view_count><?php echo htmlspecialchars($pick['views']); ?></view_count>
            <upload_time><?php echo strtotime($pick['uploaded']); ?></upload_time>
            <comment_count><?php if ($pick['comments'] != 0) { ?><?php echo htmlspecialchars($pick['comments']); ?><? } else { ?>None<? } ?></comment_count>
            <tags><?php echo htmlspecialchars($pick['tags']); ?></tags>
            <url>http://www.youtube.com/watch?v=<?php echo htmlspecialchars($pick['vid']); ?></url>
            <thumbnail_url>http://static.youtube.com/get_still?video_id=<?php echo htmlspecialchars($pick['vid']); ?></thumbnail_url>
        </video>
<? } ?>
    </video_list>
</ut_response>
    </string></value></param></params>
</methodResponse>
<? } elseif ($methodName == "youtube.videos.get_details") { 
foreach ($methodCall->params->param->value->struct->member as $xmlmember) {
	if ((string) $xmlmember->name == 'video_id') {
		$xmlvideo_id = (string) $xmlmember->value->string;
	}
}
if(!isset($xmlvideo_id)) {
echo $missingparam;
echo $ending;
die();
}
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$xmlvideo_id]);

if($video->rowCount() == 0) {
	echo '<ut_response status="fail"><error><code>102</code><description>No video was found with the specified ID.</description></error></ut_response>';
	echo $ending;
	die();
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);
$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
$video['views']->execute([$video['vid']]);
$video['views'] = $video['views']->fetchColumn();
	
//$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
//$video['comments']->execute([$video['vid']]);
//$video['comments'] = $video['comments']->fetchColumn();
$comments = $conn->prepare("SELECT * FROM comments LEFT JOIN users ON users.uid = comments.uid WHERE vidon = ? AND users.termination = 0 AND is_reply = 0 ORDER BY post_date DESC LIMIT 10");
$comments->execute([$video['vid']]);
$comments = $comments->fetchAll(PDO::FETCH_ASSOC);
switch($video['category']) {
					case '1':
						$catname = "Arts &amp; Animation";
						break;
					case '2':
						$catname = "Autos &amp; Vehicles";
						break;
					case '23':
						$catname = "Comedy";
						break;
					case '24':
						$catname = "Entertainment";
						break;
				    case '10':
						$catname = "Music";
						break;
				    case '25':
						$catname = "News &amp; Blogs";
						break;
				    case '22':
						$catname = "People";
						break;
				    case '15':
						$catname = "Pets &amp; Animals";
						break;
				    case '26':
						$catname = "Science &amp; Technology";
						break;
				    case '17':
						$catname = "Sports";
						break;
				    case '19':
						$catname = "Travel &amp; Places";
						break;
				    case '20':
						$catname = "Video Games";
						break;
					default:
						$catname = "Entertainment";
				}
?>

<ut_response status="ok">
    <video_details>
        <author><?php echo htmlspecialchars($uploader['username']); ?></author>
        <title><?php echo htmlspecialchars($video['title']); ?></title>
        <rating_avg><? echo htmlspecialchars(getRatingAverage($video['vid'])); ?></rating_avg>
        <rating_count><? echo htmlspecialchars(getRatingCount($video['vid'])); ?></rating_count>
        <tags><?php echo htmlspecialchars($video['tags']); ?></tags>
        <description><?php echo htmlspecialchars($video['description']); ?></description>
        <update_time><?php echo strtotime($video['updated']); ?></update_time>
        <view_count><?php echo htmlspecialchars($video['views']); ?></view_count>
        <upload_time><?php echo strtotime($video['uploaded']); ?></upload_time>
        <length_seconds><?php echo htmlspecialchars($video['time']); ?></length_seconds>
        <recording_date<?php if ($video['recorddate'] != NULL) { ?>><?php echo htmlspecialchars($video['recorddate']); ?></recording_date><? } else { ?> /><? } ?>

        <recording_location<?php if ($video['address'] != NULL) { ?>><?php echo htmlspecialchars($video['address']); ?></recording_location><? } else { ?> /><? } ?>

        <recording_country<?php if ($video['addrcountry'] != NULL) { ?>><?php echo htmlspecialchars($video['addrcountry']); ?></recording_country><? } else { ?> /><? } ?>

        <comment_list>
<?php if($comments !== false) {
foreach($comments as $comment) {
?>
            <comment>
                <author><?php echo htmlspecialchars($comment['username']); ?></author>
                <text><?php echo htmlspecialchars($comment['body']); ?></text>
                <time><?php echo strtotime($comment['post_date']); ?></time>
            </comment>
<? } } ?>
        </comment_list>
        <channel_list>
            <channel><?php echo $catname; ?></channel>
        </channel_list>
        <thumbnail_url>http://static205.youtube.com/vi/<?php echo htmlspecialchars($video['vid']); ?>/2.jpg</thumbnail_url>
    </video_details>
</ut_response>
    </string></value></param></params>
</methodResponse>
<? } elseif ($methodName == "youtube.users.get_profile") { 
foreach ($methodCall->params->param->value->struct->member as $xmlmember) {
	if ((string) $xmlmember->name == 'user') {
		$xmluser = (string) $xmlmember->value->string;
	}
}

if(!isset($xmluser)) {
echo $missingparam;
echo $ending;
die();
}

$profile = $conn->prepare("SELECT * FROM users WHERE users.username = ?");
$profile->execute([$xmluser]);

if($profile->rowCount() == 0) {
	echo '<ut_response status="fail"><error><code>101</code><description>No user was found with the specified username.</description></error></ut_response>';
	echo $ending;
	die();
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}
    $profile['videos'] = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND privacy = 1 AND converted = 1");
    $profile['videos']->execute([$profile["uid"]]);
    $profile['videos'] = $profile['videos']->rowCount();

    $profile['favorites'] = $conn->prepare("SELECT fid FROM favorites WHERE uid = ?");
    $profile['favorites']->execute([$profile["uid"]]);
    $profile['favorites'] = $profile['favorites']->rowCount();

    $profile['watched'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE uid = ?");
    $profile['watched']->execute([$profile['uid']]);
    $profile['watched'] = $profile['watched']->fetchColumn();
?>

<ut_response status="ok">
    <user_profile>
        <first_name><?php echo htmlspecialchars($profile['name']); ?></first_name>
        <last_name>Jones</last_name>
        <about_me><?php echo htmlspecialchars($profile['about']); ?></about_me>
        <age><?php if ($profile['birthday'] != '0000-00-00' && $profile['birthday'] != NULL) { echo str_replace(' years ago', '', timeAgo($profile['birthday'])); } ?></age>
        <video_upload_count><?php echo htmlspecialchars($profile['videos']); ?></video_upload_count>
        <video_watch_count><?php echo htmlspecialchars($profile['watched']); ?></video_watch_count>
        <homepage><?php echo htmlspecialchars($profile['website']); ?></homepage>
        <hometown><?php echo htmlspecialchars($profile['hometown']); ?></hometown>
        <gender><?php
					switch($profile['gender']) {
						case '0':
							break;
						case '1':
							echo "m";
							break;
						case '2':
							echo "f";
							break;
                        case '3':
						echo "o";
						break;
                        default:
                        echo "o";
                        break;
					}
				?></gender>
        <occupations><?php echo htmlspecialchars($profile['occupations']); ?></occupations>
        <companies><?php echo htmlspecialchars($profile['companies']); ?></companies>
        <city><?php echo htmlspecialchars($profile['city']); ?></city>
        <country><?php echo htmlspecialchars($profile['country']); ?></country>
        <books><?php echo htmlspecialchars($profile['books']); ?></books>
        <hobbies><?php echo htmlspecialchars($profile['hobbies']); ?></hobbies>
        <movies><?php echo htmlspecialchars($profile['fav_media']); ?></movies>
        <relationship><?php
					    switch($profile['relationship']) {
						case '0':
							echo "open";
							break;
						case '1':
							echo "single";
							break;
						case '2':
							echo "taken";
							break; 
                            default:
                        echo "open";
                         break; }?></relationship>
        <friend_count>5</friend_count>
        <favorite_video_count><?php echo htmlspecialchars($profile['favorites']); ?></favorite_video_count>
        <currently_on>false</currently_on>
    </user_profile>
</ut_response>
    </string></value></param></params>
</methodResponse>
<? } elseif ($methodName == "youtube.users.list_friends") {  
?>

<ut_response status="ok">
    <friend_list>
        <friend>
            <user>username1</user>
            <video_upload_count>1</video_upload_count>
            <favorite_count>2</favorite_count>
            <friend_count>3</friend_count>
        </friend>
        <friend>
            <user>username2</user>
            <video_upload_count>5</video_upload_count>
            <favorite_count>3</favorite_count>
            <friend_count>2</friend_count>
        </friend>
        ... and more ...
    </friend_list>
</ut_response>
    </string></value></param></params>
</methodResponse>
<? } elseif ($methodName == "youtube.videos.get_video_info") { 
foreach ($methodCall->params->param->value->struct->member as $xmlmember) {
	if ((string) $xmlmember->name == 'video_id') {
		$xmlvideo_id = (string) $xmlmember->value->string;
	}
}
if(!isset($xmlvideo_id)) {
echo $missingparam;
echo $ending;
die();
}
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$xmlvideo_id]);

if($video->rowCount() == 0) {
	echo '<ut_response status="ok"><video><embed_status>unavail</embed_status></video></ut_response>';
	echo $ending;
	die();
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);
if($uploader['termination'] == 1) {
	echo '<ut_response status="ok"><video><embed_status>rejected</embed_status></video></ut_response>';
	echo $ending;
	die();
}
$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
$video['views']->execute([$video['vid']]);
$video['views'] = $video['views']->fetchColumn();
	
$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
$video['comments']->execute([$video['vid']]);
$video['comments'] = $video['comments']->fetchColumn();
if($video['allow_votes'] == 1) {
	$allow_ratings = "yes";
} else {
	$allow_ratings = "no";
}
if($video['allow_embed'] == 1) {
	$embed_status = "ok";
} else {
	$embed_status = "not_allowed";
}
?>

<ut_response status="ok">
<video>
<author><?php echo htmlspecialchars($uploader['username']); ?></author>
<id><?php echo htmlspecialchars($video['vid']); ?></id>
<title><?php echo htmlspecialchars($video['title']); ?></title>
<length_seconds><?php echo htmlspecialchars($video['time']); ?></length_seconds>
<rating_avg><? echo htmlspecialchars(getRatingAverage($video['vid'])); ?></rating_avg>
<rating_count><? echo htmlspecialchars(getRatingCount($video['vid'])); ?></rating_count>
<description>
<?php echo htmlspecialchars($video['description']); ?>
</description>
<view_count><?php echo htmlspecialchars($video['views']); ?></view_count>
<upload_time><?php echo strtotime($video['uploaded']); ?></upload_time>
<comment_count><?php echo htmlspecialchars($video['comments']); ?></comment_count>
<tags>
<?php echo htmlspecialchars($video['tags']); ?>
</tags>
<url>/watch?v=<?php echo htmlspecialchars($video['vid']); ?></url>
<thumbnail_url>/get_still.php?video_id=<?php echo htmlspecialchars($video['vid']); ?></thumbnail_url>
<embed_status><?php echo $embed_status; ?></embed_status>
<allow_ratings><?php echo $allow_ratings; ?></allow_ratings>
</video>
</ut_response>
    </string></value></param></params>
</methodResponse>
<?
} elseif ($methodName == "youtube.videos.track_embed_video") {
echo '<ut_response status="ok"><track_embed>1</track_embed></ut_response>';
echo $ending;
} elseif (empty($methodName) || !isset($methodName)) { 
echo '<ut_response status="fail"><error><code>5</code><description>No method specified.</description></error></ut_response>';
echo $ending;
} else {
echo '<ut_response status="fail"><error><code>6</code><description>Unknown method specified.</description></error></ut_response>';
echo $ending;
}
?>