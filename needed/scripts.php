<?php
require 'D:\datoob\htdocs\vendor\autoload.php';
ini_set('display_errors', 0); ini_set('display_startup_errors', 0); 
//Setup site
ob_start(); // Makes redirects easier.
$config = parse_ini_file(__DIR__ . "/config.ini");
try {
	$conn = new PDO("mysql:host=".$config["servername"].";dbname=".$config["dbname"], $config["username"], $config["password"]);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
echo "<h1>Connection failed.</h1>";
}

global $conn;
session_set_cookie_params(3600 * 24 * 7); // Sessions last for one week now. So Much Win
session_start(); // This allows sessions to work. If this is removed, you cannot log in.

if($_SESSION['uid']) {
    $session = $conn->prepare("SELECT * FROM users WHERE uid = :uid");
    $session->bindParam(":uid", $_SESSION['uid']);
    $session->execute();
    if(!$session->rowCount()) {
        header("Location: logout.php");
        die();
    } else {
        $session = $session->fetch(PDO::FETCH_ASSOC);
    }
} 

$version_of_tape = "kamtape";
$_KAMTAPECONF = $conn->prepare('SELECT * FROM kamtape_web WHERE version = ? ORDER BY version DESC LIMIT 1');
$_KAMTAPECONF->execute([$version_of_tape]);
if($_KAMTAPECONF->rowCount() == 0) {
	die("<h1>This version of KamTape was not set up correctly</h1>");
} else {
	$_KAMTAPECONF = $_KAMTAPECONF->fetch(PDO::FETCH_ASSOC);
}
function invokethConfig($where) {
    global $_KAMTAPECONF;
    if (array_key_exists($where, $_KAMTAPECONF)) {
        return htmlspecialchars($_KAMTAPECONF[$where]);
    } else {
        return null;
    }
}
// sets cookie to 4 days -- not a week because i dont got that much trust in people
$maintenance_expires = time() + (4 * 24 * 60 * 60);
if($session['staff'] == 1) {
setcookie("hates__dwntime", "thats_right", $maintenance_expires);
// $_COOKIE['hates__dwntime'] == "thats_right"
}
    if (!isset($_COOKIE['hates__dwntime']) && invokethConfig("maintenance") == 1){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/index_down.php';
    die();
    }
	ini_set('display_errors', 0); ini_set('display_startup_errors', 0); 
// Set timezone so everything matches up.
date_default_timezone_set('America/Los_Angeles');
 $conn->exec("SET time_zone = '-7:00'");

$current_page = basename($_SERVER['PHP_SELF']);
$current_page = str_replace('.php', '', $current_page);
//Functions
function alert($error, $type = "success") {
    // alert ("Your error here", "error")
    // Success (default) == gray-bluish
    // Error == red
    // process == orange. Only use i've seen is from the video upload success page from 2006
    if($type == "success") {
    echo '<div class="confirmBox">
		'.$error.'
	</div>
	<br>';
    } else if($type == "error") {
      echo '<div class="errorBox">
		'.$error.'
	</div>';  
    } else if($type == "process") {
    echo '<table width="100%" align="center" bgcolor="#d86c2f" cellpadding="6" cellspacing="3" border="0">
		<tbody><tr>
			<td align="center" bgcolor="#FFFFFF"><span class="error" style="color: #d86c2f;">'.$error.'</span></td>
		</tr>
	</tbody></table></br>';  
    }
}
function encrypt($data) {
    $ivSize = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($ivSize);
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $config["aeskey"], OPENSSL_RAW_DATA, $iv);
    $encryptedData = base64_encode($iv . $encrypted);
    return $encryptedData;
}
function unique_word_count($string) {
    $string = explode(' ', strtolower($string));
    $words = array_unique($string);
    return count($words);
}
function decrypt($encryptedData) {
    $encryptedData = base64_decode($encryptedData);
    $ivSize = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($encryptedData, 0, $ivSize);
    $encrypted = substr($encryptedData, $ivSize);
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $config["aeskey"], OPENSSL_RAW_DATA, $iv);
    $decrypted = htmlspecialchars($decrypted);
    $decrypted = nl2br($decrypted);
    return $decrypted;
}
function retroDate($date, $format = "F j, Y") {
    // Short script to make old dates easier.
    // Accommodates for past leap years too! ^_^
    // 08/30/05: This was the first thing ever coded for KamTape. When it begin, it was just me and playing around with this function. Good times. 
  $dateTime = new DateTime($date);
  $dateTime->modify("-18 years");
  return $dateTime->format($format);
} 

function commentTimeAgo($timestamp) {
    $currentTimestamp = time();
    $timeAgo = $currentTimestamp - $timestamp;

    $days = floor($timeAgo / (60 * 60 * 24));
    $hours = floor(($timeAgo % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor(($timeAgo % (60 * 60)) / 60);

    $result = '';
    if ($days > 0) {
        $result .= $days . ' day' . ($days != 1 ? 's' : '') . ', ';
    }
    if ($hours > 0) {
        $result .= $hours . ' hour' . ($hours != 1 ? 's' : '') . ', ';
    }
    $result .= $minutes . ' minute' . ($minutes != 1 ? 's' : '') . ' ago';

    return $result;
}

function pluralize($number, $singular, $plural) {
    if ($number === 1) {
        return $number . ' ' . $singular;
    } else {
        return $number . ' ' . $plural;
    }
}
function redirect($url) {
    header("Location: " . $url);
    exit();
}
function error() {
    header("Location: /error.html");
    exit();
}

function force_login() {
if($_SESSION['uid'] == NULL) {
header("Location: signup_login.php?next=". $_SERVER['REQUEST_URI']);
}
}

function whos_online($many = 4, $width = 180) { 
    $lastonline = $GLOBALS['conn']->query("SELECT * FROM users WHERE termination = 0 ORDER BY users.last_act DESC LIMIT $many");
    ?>
<div style="padding-top: 10px;">
		<table width="<?php echo htmlspecialchars($width); ?>" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#EEEEDD">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td width="<?php echo htmlspecialchars($width - 10); ?>">
				
				<div style="padding: 2px 5px 10px 5px;">
				<div style="font-size: 14px; font-weight: bold; margin-bottom: 8px; color: #666633;">Last <?php echo htmlspecialchars($many); ?> users online...</div>
                <?php foreach($lastonline as $user) { 
                    $user['vids'] = $GLOBALS['conn']->prepare("SELECT COUNT(vid) FROM videos WHERE uid = ? AND converted = 1");
				    $user['vids']->execute([$user['uid']]);
				    $user['vids'] = $user['vids']->fetchColumn();

                    $user['favs'] = $GLOBALS['conn']->prepare("SELECT COUNT(fid) FROM favorites WHERE uid = ?");
				    $user['favs']->execute([$user['uid']]);
				    $user['favs'] = $user['favs']->fetchColumn();
                    ?>
				
					<div style="font-size: 12px; font-weight: bold; margin-bottom: 5px;"><a href="profile.php?user=<?php echo htmlspecialchars($user['username']); ?>" <? if (strlen($user['username']) > 14) { ?> title="<?= htmlspecialchars($user['username']) ?>" <? } ?>><?php
echo shorten($user['username'], 14);
?></a></div>

					<div style="font-size: 12px; margin-bottom: 8px; padding-bottom: 10px; border-bottom: 1px dashed #CCCC66;"><a href="profile_videos.php?user=<?php echo htmlspecialchars($user['username']); ?>"><img src="img/icon_vid.gif" alt="Videos" width="14" height="14" border="0" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"></a> (<a href="profile_videos.php?user=<?php echo htmlspecialchars($user['username']); ?>"><?php echo $user['vids']; ?></a>)
					 | <a href="profile_favorites.php?user=<?php echo htmlspecialchars($user['username']); ?>"><img src="img/icon_fav.gif" alt="Favorites" width="14" height="14" border="0" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"></a> (<a href="profile_favorites.php?user=<?php echo htmlspecialchars($user['username']); ?>"><?php echo $user['favs']; ?></a>)
					 | <a href="profile_friends.php?user=<?php echo htmlspecialchars($user['username']); ?>"><img src="img/icon_friends.gif" alt="Friends" width="14" height="14" border="0" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"></a> (<a href="profile_friends.php?user=<?php echo htmlspecialchars($user['username']); ?>">0</a>)</div><? } ?>


									
				<div style="font-weight: bold; margin-bottom: 5px;">Icon Key:</div>
				<div style="margin-bottom: 4px;"><img src="img/icon_vid.gif" alt="Videos" width="14" height="14" border="0" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"> - Videos</div>
				<div style="margin-bottom: 4px;"><img src="img/icon_fav.gif" alt="Favorites" width="14" height="14" border="0" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"> - Favorites</div>
				<img src="img/icon_friends.gif" alt="Friends" width="14" height="14" border="0" style="vertical-align: text-bottom; padding-left: 2px; padding-right: 1px;"> - Friends

				</div>

				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</div>
        <?php
}
function shorten($text, $number, $symbols = '...') {
    $text = htmlspecialchars($text);
    $new = (strlen($text) > $number) ? substr($text, 0, $number) . $symbols : $text;
    return $new;
}
function timeAgo($date) {
  $now = time();
  $time = strtotime($date);
  $diff = $now - $time;

  $periods = array(
    "year",
    "month",
    "week",
    "day",
    "hour",
    "minute",
    "second"
  );
  $lengths = array(
    31556926,
    2629743,
    604800,
    86400,
    3600,
    60,
    1
  );

  $difference = "";
  for ($i = 0; $i < count($lengths); $i++) {
    if ($diff >= $lengths[$i]) {
      $number = floor($diff / $lengths[$i]);
      $difference .= $number . " " . $periods[$i];
      if ($number != 1) {
        $difference .= "s";
      }
      $difference .= " ago";
      break;
    }
  }
  if (empty($difference)) {
    $difference = "0 seconds ago";
  }
  return $difference;
}

function generateId($length = 11) {
	$char_array = [];
	$char_range = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';
	for($a = 0; $a < $length; $a++) {
		$char_array[$a] = $char_range[rand(0, strlen($char_range) - 1)];
	}
	return implode("", $char_array);
}
 // You'd think this took a good while... It didn't. I can finally make an accurate country dropdown without any hassle thanks to this: http://code-cocktail.in/tools/convert-selectbox-to-array/#
      $_COUNTRIES = [
  NULL => '---',
  'US' => 'United States',
  'AF' => 'Afghanistan',
  'AL' => 'Albania',
  'DZ' => 'Algeria',
  'AS' => 'American Samoa',
  'AD' => 'Andorra',
  'AO' => 'Angola',
  'AI' => 'Anguilla',
  'AG' => 'Antigua and Barbuda',
  'AR' => 'Argentina',
  'AM' => 'Armenia',
  'AW' => 'Aruba',
  'AU' => 'Australia',
  'AT' => 'Austria',
  'AZ' => 'Azerbaijan',
  'BS' => 'Bahamas',
  'BH' => 'Bahrain',
  'BD' => 'Bangladesh',
  'BB' => 'Barbados',
  'BY' => 'Belarus',
  'BE' => 'Belgium',
  'BZ' => 'Belize',
  'BJ' => 'Benin',
  'BM' => 'Bermuda',
  'BT' => 'Bhutan',
  'BO' => 'Bolivia',
  'BA' => 'Bosnia and Herzegovina',
  'BW' => 'Botswana',
  'BV' => 'Bouvet Island',
  'BR' => 'Brazil',
  'IO' => 'British Indian Ocean Territory',
  'VG' => 'British Virgin Islands',
  'BN' => 'Brunei',
  'BG' => 'Bulgaria',
  'BF' => 'Burkina Faso',
  'BI' => 'Burundi',
  'KH' => 'Cambodia',
  'CM' => 'Cameroon',
  'CA' => 'Canada',
  'CV' => 'Cape Verde',
  'KY' => 'Cayman Islands',
  'CF' => 'Central African Republic',
  'TD' => 'Chad',
  'CL' => 'Chile',
  'CN' => 'China',
  'CX' => 'Christmas Island',
  'CC' => 'Cocos (Keeling) Islands',
  'CO' => 'Colombia',
  'KM' => 'Comoros',
  'CG' => 'Congo',
  'CD' => 'Congo - Democratic Republic of',
  'CK' => 'Cook Islands',
  'CR' => 'Costa Rica',
  'CI' => 'Cote d\'Ivoire',
  'HR' => 'Croatia',
  'CU' => 'Cuba',
  'CY' => 'Cyprus',
  'CZ' => 'Czech Republic',
  'DK' => 'Denmark',
  'DJ' => 'Djibouti',
  'DM' => 'Dominica',
  'DO' => 'Dominican Republic',
  'TP' => 'East Timor',
  'EC' => 'Ecuador',
  'EG' => 'Egypt',
  'SV' => 'El Salvador',
  'GQ' => 'Equitorial Guinea',
  'ER' => 'Eritrea',
  'EE' => 'Estonia',
  'ET' => 'Ethiopia',
  'FK' => 'Falkland Islands (Islas Malvinas)',
  'FO' => 'Faroe Islands',
  'FJ' => 'Fiji',
  'FI' => 'Finland',
  'FR' => 'France',
  'GF' => 'French Guyana',
  'PF' => 'French Polynesia',
  'TF' => 'French Southern and Antarctic Lands',
  'GA' => 'Gabon',
  'GM' => 'Gambia',
  'GZ' => 'Gaza Strip',
  'GE' => 'Georgia',
  'DE' => 'Germany',
  'GH' => 'Ghana',
  'GI' => 'Gibraltar',
  'GR' => 'Greece',
  'GL' => 'Greenland',
  'GD' => 'Grenada',
  'GP' => 'Guadeloupe',
  'GU' => 'Guam',
  'GT' => 'Guatemala',
  'GN' => 'Guinea',
  'GW' => 'Guinea-Bissau',
  'GY' => 'Guyana',
  'HT' => 'Haiti',
  'HM' => 'Heard Island and McDonald Islands',
  'VA' => 'Holy See (Vatican City)',
  'HN' => 'Honduras',
  'HK' => 'Hong Kong',
  'HU' => 'Hungary',
  'IS' => 'Iceland',
  'IN' => 'India',
  'ID' => 'Indonesia',
  'IR' => 'Iran',
  'IQ' => 'Iraq',
  'IE' => 'Ireland',
  'IL' => 'Israel',
  'IT' => 'Italy',
  'JM' => 'Jamaica',
  'JP' => 'Japan',
  'JO' => 'Jordan',
  'KZ' => 'Kazakhstan',
  'KE' => 'Kenya',
  'KI' => 'Kiribati',
  'KW' => 'Kuwait',
  'KG' => 'Kyrgyzstan',
  'LA' => 'Laos',
  'LV' => 'Latvia',
  'LB' => 'Lebanon',
  'LS' => 'Lesotho',
  'LR' => 'Liberia',
  'LY' => 'Libya',
  'LI' => 'Liechtenstein',
  'LT' => 'Lithuania',
  'LU' => 'Luxembourg',
  'MO' => 'Macau',
  'MK' => 'Macedonia - The Former Yugoslav Republic of',
  'MG' => 'Madagascar',
  'MW' => 'Malawi',
  'MY' => 'Malaysia',
  'MV' => 'Maldives',
  'ML' => 'Mali',
  'MT' => 'Malta',
  'MH' => 'Marshall Islands',
  'MQ' => 'Martinique',
  'MR' => 'Mauritania',
  'MU' => 'Mauritius',
  'YT' => 'Mayotte',
  'MX' => 'Mexico',
  'FM' => 'Micronesia - Federated States of',
  'MD' => 'Moldova',
  'MC' => 'Monaco',
  'MN' => 'Mongolia',
  'MS' => 'Montserrat',
  'MA' => 'Morocco',
  'MZ' => 'Mozambique',
  'MM' => 'Myanmar',
  'NA' => 'Namibia',
  'NR' => 'Naura',
  'NP' => 'Nepal',
  'NL' => 'Netherlands',
  'AN' => 'Netherlands Antilles',
  'NC' => 'New Caledonia',
  'NZ' => 'New Zealand',
  'NI' => 'Nicaragua',
  'NE' => 'Niger',
  'NG' => 'Nigeria',
  'NU' => 'Niue',
  'NF' => 'Norfolk Island',
  'KP' => 'North Korea',
  'MP' => 'Northern Mariana Islands',
  'NO' => 'Norway',
  'OM' => 'Oman',
  'PK' => 'Pakistan',
  'PW' => 'Palau',
  'PA' => 'Panama',
  'PG' => 'Papua New Guinea',
  'PY' => 'Paraguay',
  'PE' => 'Peru',
  'PH' => 'Philippines',
  'PN' => 'Pitcairn Islands',
  'PL' => 'Poland',
  'PT' => 'Portugal',
  'PR' => 'Puerto Rico',
  'QA' => 'Qatar',
  'RE' => 'Reunion',
  'RO' => 'Romania',
  'RU' => 'Russia',
  'RW' => 'Rwanda',
  'KN' => 'Saint Kitts and Nevis',
  'LC' => 'Saint Lucia',
  'VC' => 'Saint Vincent and the Grenadines',
  'WS' => 'Samoa',
  'SM' => 'San Marino',
  'ST' => 'Sao Tome and Principe',
  'SA' => 'Saudi Arabia',
  'SN' => 'Senegal',
  'CS' => 'Serbia and Montenegro',
  'SC' => 'Seychelles',
  'SL' => 'Sierra Leone',
  'SG' => 'Singapore',
  'SK' => 'Slovakia',
  'SI' => 'Slovenia',
  'SB' => 'Solomon Islands',
  'SO' => 'Somalia',
  'ZA' => 'South Africa',
  'GS' => 'South Georgia and the South Sandwich Islands',
  'KR' => 'South Korea',
  'ES' => 'Spain',
  'LK' => 'Sri Lanka',
  'SH' => 'St. Helena',
  'PM' => 'St. Pierre and Miquelon',
  'SD' => 'Sudan',
  'SR' => 'Suriname',
  'SJ' => 'Svalbard',
  'SZ' => 'Swaziland',
  'SE' => 'Sweden',
  'CH' => 'Switzerland',
  'SY' => 'Syria',
  'TW' => 'Taiwan',
  'TJ' => 'Tajikistan',
  'TZ' => 'Tanzania',
  'TH' => 'Thailand',
  'TG' => 'Togo',
  'TK' => 'Tokelau',
  'TO' => 'Tonga',
  'TT' => 'Trinidad and Tobago',
  'TN' => 'Tunisia',
  'TR' => 'Turkey',
  'TM' => 'Turkmenistan',
  'TC' => 'Turks and Caicos Islands',
  'TV' => 'Tuvalu',
  'UG' => 'Uganda',
  'UA' => 'Ukraine',
  'AE' => 'United Arab Emirates',
  'GB' => 'United Kingdom',
  'VI' => 'United States Virgin Islands',
  'UY' => 'Uruguay',
  'UZ' => 'Uzbekistan',
  'VU' => 'Vanuatu',
  'VE' => 'Venezuela',
  'VN' => 'Vietnam',
  'WF' => 'Wallis and Futuna',
  'PS' => 'West Bank',
  'EH' => 'Western Sahara',
  'YE' => 'Yemen',
  'ZM' => 'Zambia',
  'ZW' => 'Zimbabwe',
   ]; function getCountryName($isoCode) {
       // What I did earlier was really fuxxing newby, here's a better version
      global $_COUNTRIES;
    if (isset($_COUNTRIES[$isoCode])) {
        return $_COUNTRIES[$isoCode];
    } else {
        return '???';
    }
}
if ($session['termination'] == 1) {
   session_start();
   session_destroy(); 
   redirect("index.php");
}

// Here's a better online detector. Basically I couldn't find any efficent code for making an accurate online detector in this type of codebase, so this is currently the best we got.
if(!empty($session['uid'])){
$lastlogin = $conn->prepare("UPDATE users SET last_act = CURRENT_TIMESTAMP WHERE uid = ?");
$lastlogin->execute([$session['uid']]);
}
function drawStars($rating, $size = "L", $extras = NULL) {
    if ($size == "L") { // Use == for comparison
        $star_half_icon = "star_half.gif";
        $star_none_icon = "star_bg.gif";
        $star_full_icon = "star.gif";
    }

    if ($size == "SM") { // Use == for comparison
        $star_half_icon = "star_sm_half.gif";
        $star_none_icon = "star_sm_bg.gif";
        $star_full_icon = "star_sm.gif";  
    }

    if(fmod($rating, 1) !== 0.00){
        $rating_half = true;
    } else {
        $rating_half = false;
    }
    
    $star_rating_draw = ''; // Initialize the variable
    
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $star_rating_draw .= '<img '. $extras. ' src="/img/' . $star_full_icon . '">
';
        } elseif ($rating_half && $rating > ($i - 1) && $rating < $i) {
            $star_rating_draw .= '<img '. $extras. ' src="/img/' . $star_half_icon . '">
';
        } else {
            $star_rating_draw .= '<img '. $extras. ' src="/img/' . $star_none_icon . '">
';
        }
    }

    echo $star_rating_draw;
}
function getRatingCount($vid) {
    $ratingscount = $GLOBALS['conn']->prepare("SELECT COUNT(rating) FROM ratings WHERE video = ?");
    $ratingscount->execute([$vid]);
    $ratingscount = $ratingscount->fetchColumn();
    return $ratingscount;

}

function grabRatings($vid, $size = "L", $extras = NULL) {
    $avg = $GLOBALS['conn']->prepare("SELECT AVG(rating) FROM ratings WHERE video = ?");
    $avg->execute([$vid]);
    $average = $avg->fetchColumn();
    drawStars($average, $size, $extras);

}

function getRatingAverage($vid) {
    $avg = $GLOBALS['conn']->prepare("SELECT AVG(rating) FROM ratings WHERE video = ?");
    $avg->execute([$vid]);
    $average = $avg->fetchColumn();
	if($average == NULL) {
	$average = 0;
	}
    return $average;

}

function grabRatingsPage($vid, $size = "SM", $show_count = 1) {
    $avg = $GLOBALS['conn']->prepare("SELECT AVG(rating) FROM ratings WHERE video = ?");
    $avg->execute([$vid]);
    $average = $avg->fetchColumn();
    if($average != 0) {
    drawStars($average, "SM", 'style="border:0px; padding:0px; margin:0px; vertical-align:middle;"'); if($show_count == 1 && $average != 0) { echo '&nbsp;<span style="color:#666666; font-size:smaller; ">('. htmlspecialchars(getRatingCount($vid)) .' ratings)</span>';}
    }
}

function honorsPageNum($honornum) {
	switch($honornum) {
		case $honornum <= 20:
		return 1;
		break;
		case $honornum <= 40:
		return 2;
		break;
		case $honornum <= 60:
		return 3;
		break;
		case $honornum <= 80:
		return 4;
		break;
		case $honornum <= 100:
		return 5;
		break;
	}
}

function dec2hex($int) {
  $hex = dechex($int);
  if (strlen($hex)%2 != 0) {
    $hex = str_pad($hex, strlen($hex) + 1, '0', STR_PAD_LEFT);
  }
  return $hex;
}

function session_error_index($message, $type = "error", $location = "index.php") {
	if ($type == "success") {
	$picklestart = hex2bin("80027d710128550c6572726f725f6669656c64737102635f5f6275696c74696e5f5f0a7365740a71035d8552710455066572726f727371055d710655086d6573736167657371075d710855");
	$pickleend = hex2bin("710961752e");
	} elseif ($type == "error") {
	$picklestart = hex2bin("80027d710128550c6572726f725f6669656c64737102635f5f6275696c74696e5f5f0a7365740a71035d8552710455066572726f727371055d710655");
	$pickleend = hex2bin("71076155086d6573736167657371085d7109752e");
	}
	$picklenum = hex2bin(dec2hex(strlen($message)));
    $error_base64 = strtr(base64_encode($picklestart.$picklenum.$message.$pickleend), '+/', '-_');
    redirect($location."?&session=".$error_base64);
}

?>