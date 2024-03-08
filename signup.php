<?php 
require "needed/start.php";

if($_SESSION['uid'] != NULL) {
	header("Location: index.php");
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["current_form"] == "signupForm") {
   $param_ip = password_hash($_SERVER['REMOTE_ADDR'], PASSWORD_DEFAULT); // Hash IPs too!
        $stmt = $conn->prepare("SELECT uid FROM users WHERE ip = :address");
        $stmt->execute([
            ':address' => $param_ip,
        ]);
        if($stmt->rowCount() > 15){
            $username_err = "Sorry, you have too many accounts.";
        }
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else if(!preg_match('/^[a-zA-Z0-9]+$/', trim($_POST["username"]))){
        $username_err = "Sorry, that user name contains special characters.";
    } else if (strlen($param_username) > 20) {
    $username_err = "Sorry, that user name is too long.";
    } else {
        // Prepare a select statement and bind variables to the prepared statement as parameters
        $param_username = trim($_POST["username"]);
        $stmt = $conn->prepare("SELECT uid FROM users WHERE username = :username");
        $stmt->execute([
            ':username' => $param_username,
        ]);
        if($stmt->rowCount() > 0){
            $username_err = "Sorry, that user name has already been taken.";
        }
    }
    if (isset($_POST["username"]) && stripos($_POST["username"], "kamtape") !== false) {
            $username_err = "Sorry, a user name can not contain the word 'kamtape'.";
    }
    // Validate password
    if(empty(trim($_POST["password1"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password1"])) < 8){
        $password_err = "Your password is too short.";
    } else{
        $password = trim($_POST["password1"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["password2"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["password2"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Your passwords didn't match; try retyping them.";
        }
    }
	
	// Validate email
    if (substr($_POST['email'], -strlen("kamtape.com")) === "kamtape.com") {
    $email_err = "Sorry, this email is invalid.";
    }
	if(empty(trim($_POST['email']))) {
		$email_err = "Please enter an email.";
	} elseif(!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
		$email_err = "Sorry, this email is invalid.";
	} else {
		$param_email = trim($_POST['email']);
		
		// Prepare a select statement and bind variables to the prepared statement as parameters
		$email_in_use = $conn->prepare("SELECT uid FROM users WHERE email = ?");
		$email_in_use->execute([$param_email]);
		if($email_in_use->rowCount() > 0) {
			$email_err = "Sorry, somebody is already using this e mail.";
		}
	}
    
	// Validate age
	if ((date("Y") - trim($_POST['birthday_yr'])) < 13) {
		header("Location: /signup_copa");
		die();
	}
	
	// Validate captcha
	if ($_POST['response'] != $_SESSION['captcha']) {
		$captcha_err = "The text you entered does not match the text in the captcha.";
	}
	
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($captcha_err)){ 
		// Set parameters
        $param_id = generateId();
		$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

		$stmt = $conn->prepare("INSERT INTO users (uid, username, password, email, ip) VALUES (:uid, :username, :password, :email, :ip)");
		$stmt->execute([
            ':uid' => $param_id,
			':username' => $param_username,
			':password' => $param_password,
			':email' => $param_email,
			':ip' => $param_ip 
		]);
        $_SESSION['uid'] = $param_id;
		// Redirect to login page
        header("Location: /signup_invite.php");
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["current_form"] == "loginForm") {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$member = $conn->prepare("SELECT uid, username, password, termination FROM users WHERE username LIKE :username");
		$member->execute([":username" => trim($_POST['username'])]);
        
		if($member->rowCount() > 0) {
			$member = $member->fetch(PDO::FETCH_ASSOC);
            if($member['termination'] == 1) {
            $username_err = "This account has been terminated.";    
            }
			if(password_verify(trim($_POST['password']), $member['password']) && $member['termination'] !== 1) {
				$_SESSION['uid'] = $member['uid'];
				header("Location: index.php");
				$lastlogin = $conn->prepare("UPDATE users SET lastlogin = CURRENT_TIMESTAMP WHERE uid = ?");
				$lastlogin->execute([$member['uid']]);
			} else {
                if($member['termination'] !== 1) {
				$password_err = "Password is incorrect!";
                }
			}
		} else {
			$username_err = "That user doesn't exist!";
		}
	}
}

if(!empty($username_err) || !empty($password_err) || !empty($confirm_password_err) || !empty($email_err) || !empty($captcha_err)){ 
      if(!empty($username_err)) { alert(htmlspecialchars($username_err), "error"); }
      if(!empty($password_err)) { alert(htmlspecialchars($password_err), "error"); }
      if(!empty($confirm_password_err)) { alert(htmlspecialchars($confirm_password_err), "error"); }
      if(!empty($email_err)) { alert(htmlspecialchars($email_err), "error"); }
	  if(!empty($captcha_err)) { alert(htmlspecialchars($captcha_err), "error"); }
}

$captchaid = generateId(64);

?>
<div id="suSignupDiv" class="contentBox">
		<?php if ($_GET['signup_type'] == "m") { ?><h2 class="marT0">Musician Sign Up<?php } elseif ($_GET['signup_type'] == "c") { ?><h2 class="marT0">Comedian Sign Up<?php } else { ?><h2>Join YouTube<? } ?></h2>
        It's free and easy. Just fill out the account info below. <span class="smallText"><b>(All fields required)</b></span><br />
        
        
		<?php if (($_GET['signup_type'] == "m") || ($_GET['signup_type'] == "c")) { ?>
		<div class="alertBoxSm">
		YouTube <?php if ($_GET['signup_type'] == "m") { ?>Musician<?php } elseif ($_GET['signup_type'] == "c") { ?>Comedian<? } ?> Channels are for <?php if ($_GET['signup_type'] == "m") { ?>musicians<?php } elseif ($_GET['signup_type'] == "c") { ?>comedians<? } ?>.
		<br />
		<br />
		<?php if ($_GET['signup_type'] == "m") { ?>
		Uploading videos or music that you do not own is a violation of the artist's copyrights and against the law. If you upload material you do not own, your account will be deleted. 	
		<?php } elseif ($_GET['signup_type'] == "c") { ?>
		Uploading videos or material that you do not own is a copyright violation and against the law. If you upload material you do not own, your account will be deleted.
		<? } ?>
		</div>
		
		<? } ?>
	
	<br />
	
	<form name="signupForm" id="signupForm" method="post">
		<input type="hidden" name="current_form" value="signupForm" />
		<input type="hidden" name="signup_type" value="<?php if (($_GET['signup_type'] == "m") || ($_GET['signup_type'] == "c")) { echo $_GET['signup_type']; } else { ?>u<? } ?>" />
			
		
	
		
			<?php if($_GET['next'] != NULL) { ?><input type="hidden" name="next" value="<?php echo htmlspecialchars($_GET["next"]); ?>" /><? } ?>
		
	
		
	
		
	
		
	
		
	

	
		<table class="dataEntryTableSmall" border="0">
			<tr>
				<td class="formLabel"<? if(!empty($email_err)) { echo ' style="color: #ff0000;"';}?>>	<nobr>Email Address:</nobr>
</td>
				<td class="formFieldSmall" width="100"><input tabindex="1" type="text" size="25" maxlength="60" name="email" value="" /></td>
				<td rowspan="4" width="110">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<div style="margin-left:-5px;margin-top:-5px">
						<map name="upsellmap">
							<area href="/signup?signup_type=m" alt="Musicians" title="Musicians" shape=poly coords="3,11,7,30,86,21,85,1">
							<area href="/signup?signup_type=m" alt="Musicians" title="Musicians" shape=rect coords="1,59,91,74">
							<area href="/signup?signup_type=c" alt="Comedians" title="Comedians" shape=poly coords="25,49,20,29,104,19,104,39">
							<area href="/signup?signup_type=c" alt="Comedians" title="Comedians" shape=rect coords="6,73,104,89">
						</map>
						
						<img src="/img/pic_upsell_musciciancomedian_107x91.gif" border="0" width="107" height="91" usemap="#upsellmap">
					</div>
				</td>
			</tr>
			<tr>
				<td class="formLabel"<? if(!empty($username_err)) { echo ' style="color: #ff0000;"';}?>>	<nobr>User Name:</nobr>
</td>
				<td class="formFieldSmall"><input tabindex="2" type="text" size="20" maxlength="20" name="username" value="" /></td>
			</tr>
			<tr>
				<td class="formLabel"<? if(!empty($password_err)) { echo ' style="color: #ff0000;"';}?>>	<nobr>Password:</nobr>
</td>
				<td class="formFieldSmall"><input tabindex="3" type="password" size="20" maxlength="20" name="password1" value="" /></td>
			</tr>
			<tr>
				<td class="formLabel"<? if(!empty($confirm_password_err)) { echo ' style="color: #ff0000;"';}?>>	<nobr>Confirm Password:</nobr>
</td>
				<td class="formFieldSmall"><input tabindex="4" type="password" size="20" maxlength="20" name="password2" value="" /></td>
			</tr>
			<tr>
				<td class="formLabel">	<nobr>Country:</nobr>
</td>
				<td class="formFieldSmall" colspan="2">
					<select name="country" tabindex="5">
						<option>---</option>
						<option value="US" >United States</option>
						<option value="AF" >Afghanistan</option>
						<option value="AL" >Albania</option>
						<option value="DZ" >Algeria</option>
						<option value="AS" >American Samoa</option>
						<option value="AD" >Andorra</option>
						<option value="AO" >Angola</option>
						<option value="AI" >Anguilla</option>
						<option value="AG" >Antigua and Barbuda</option>
						<option value="AR" >Argentina</option>
						<option value="AM" >Armenia</option>
						<option value="AW" >Aruba</option>
						<option value="AU" >Australia</option>
						<option value="AT" >Austria</option>
						<option value="AZ" >Azerbaijan</option>
						<option value="BS" >Bahamas</option>
						<option value="BH" >Bahrain</option>
						<option value="BD" >Bangladesh</option>
						<option value="BB" >Barbados</option>
						<option value="BY" >Belarus</option>
						<option value="BE" >Belgium</option>
						<option value="BZ" >Belize</option>
						<option value="BJ" >Benin</option>
						<option value="BM" >Bermuda</option>
						<option value="BT" >Bhutan</option>
						<option value="BO" >Bolivia</option>
						<option value="BA" >Bosnia and Herzegovina</option>
						<option value="BW" >Botswana</option>
						<option value="BV" >Bouvet Island</option>
						<option value="BR" >Brazil</option>
						<option value="IO" >British Indian Ocean Territory</option>
						<option value="VG" >British Virgin Islands</option>
						<option value="BN" >Brunei</option>
						<option value="BG" >Bulgaria</option>
						<option value="BF" >Burkina Faso</option>
						<option value="BI" >Burundi</option>
						<option value="KH" >Cambodia</option>
						<option value="CM" >Cameroon</option>
						<option value="CA" >Canada</option>
						<option value="CV" >Cape Verde</option>
						<option value="KY" >Cayman Islands</option>
						<option value="CF" >Central African Republic</option>
						<option value="TD" >Chad</option>
						<option value="CL" >Chile</option>
						<option value="CN" >China</option>
						<option value="CX" >Christmas Island</option>
						<option value="CC" >Cocos (Keeling) Islands</option>
						<option value="CO" >Colombia</option>
						<option value="KM" >Comoros</option>
						<option value="CG" >Congo</option>
						<option value="CD" >Congo - Democratic Republic of</option>
						<option value="CK" >Cook Islands</option>
						<option value="CR" >Costa Rica</option>
						<option value="CI" >Cote d'Ivoire</option>
						<option value="HR" >Croatia</option>
						<option value="CU" >Cuba</option>
						<option value="CY" >Cyprus</option>
						<option value="CZ" >Czech Republic</option>
						<option value="DK" >Denmark</option>
						<option value="DJ" >Djibouti</option>
						<option value="DM" >Dominica</option>
						<option value="DO" >Dominican Republic</option>
						<option value="TP" >East Timor</option>
						<option value="EC" >Ecuador</option>
						<option value="EG" >Egypt</option>
						<option value="SV" >El Salvador</option>
						<option value="GQ" >Equitorial Guinea</option>
						<option value="ER" >Eritrea</option>
						<option value="EE" >Estonia</option>
						<option value="ET" >Ethiopia</option>
						<option value="FK" >Falkland Islands (Islas Malvinas)</option>
						<option value="FO" >Faroe Islands</option>
						<option value="FJ" >Fiji</option>
						<option value="FI" >Finland</option>
						<option value="FR" >France</option>
						<option value="GF" >French Guyana</option>
						<option value="PF" >French Polynesia</option>
						<option value="TF" >French Southern and Antarctic Lands</option>
						<option value="GA" >Gabon</option>
						<option value="GM" >Gambia</option>
						<option value="GZ" >Gaza Strip</option>
						<option value="GE" >Georgia</option>
						<option value="DE" >Germany</option>
						<option value="GH" >Ghana</option>
						<option value="GI" >Gibraltar</option>
						<option value="GR" >Greece</option>
						<option value="GL" >Greenland</option>
						<option value="GD" >Grenada</option>
						<option value="GP" >Guadeloupe</option>
						<option value="GU" >Guam</option>
						<option value="GT" >Guatemala</option>
						<option value="GN" >Guinea</option>
						<option value="GW" >Guinea-Bissau</option>
						<option value="GY" >Guyana</option>
						<option value="HT" >Haiti</option>
						<option value="HM" >Heard Island and McDonald Islands</option>
						<option value="VA" >Holy See (Vatican City)</option>
						<option value="HN" >Honduras</option>
						<option value="HK" >Hong Kong</option>
						<option value="HU" >Hungary</option>
						<option value="IS" >Iceland</option>
						<option value="IN" >India</option>
						<option value="ID" >Indonesia</option>
						<option value="IR" >Iran</option>
						<option value="IQ" >Iraq</option>
						<option value="IE" >Ireland</option>
						<option value="IL" >Israel</option>
						<option value="IT" >Italy</option>
						<option value="JM" >Jamaica</option>
						<option value="JP" >Japan</option>
						<option value="JO" >Jordan</option>
						<option value="KZ" >Kazakhstan</option>
						<option value="KE" >Kenya</option>
						<option value="KI" >Kiribati</option>
						<option value="KW" >Kuwait</option>
						<option value="KG" >Kyrgyzstan</option>
						<option value="LA" >Laos</option>
						<option value="LV" >Latvia</option>
						<option value="LB" >Lebanon</option>
						<option value="LS" >Lesotho</option>
						<option value="LR" >Liberia</option>
						<option value="LY" >Libya</option>
						<option value="LI" >Liechtenstein</option>
						<option value="LT" >Lithuania</option>
						<option value="LU" >Luxembourg</option>
						<option value="MO" >Macau</option>
						<option value="MK" >Macedonia - The Former Yugoslav Republic of</option>
						<option value="MG" >Madagascar</option>
						<option value="MW" >Malawi</option>
						<option value="MY" >Malaysia</option>
						<option value="MV" >Maldives</option>
						<option value="ML" >Mali</option>
						<option value="MT" >Malta</option>
						<option value="MH" >Marshall Islands</option>
						<option value="MQ" >Martinique</option>
						<option value="MR" >Mauritania</option>
						<option value="MU" >Mauritius</option>
						<option value="YT" >Mayotte</option>
						<option value="MX" >Mexico</option>
						<option value="FM" >Micronesia - Federated States of</option>
						<option value="MD" >Moldova</option>
						<option value="MC" >Monaco</option>
						<option value="MN" >Mongolia</option>
						<option value="MS" >Montserrat</option>
						<option value="MA" >Morocco</option>
						<option value="MZ" >Mozambique</option>
						<option value="MM" >Myanmar</option>
						<option value="NA" >Namibia</option>
						<option value="NR" >Naura</option>
						<option value="NP" >Nepal</option>
						<option value="NL" >Netherlands</option>
						<option value="AN" >Netherlands Antilles</option>
						<option value="NC" >New Caledonia</option>
						<option value="NZ" >New Zealand</option>
						<option value="NI" >Nicaragua</option>
						<option value="NE" >Niger</option>
						<option value="NG" >Nigeria</option>
						<option value="NU" >Niue</option>
						<option value="NF" >Norfolk Island</option>
						<option value="KP" >North Korea</option>
						<option value="MP" >Northern Mariana Islands</option>
						<option value="NO" >Norway</option>
						<option value="OM" >Oman</option>
						<option value="PK" >Pakistan</option>
						<option value="PW" >Palau</option>
						<option value="PA" >Panama</option>
						<option value="PG" >Papua New Guinea</option>
						<option value="PY" >Paraguay</option>
						<option value="PE" >Peru</option>
						<option value="PH" >Philippines</option>
						<option value="PN" >Pitcairn Islands</option>
						<option value="PL" >Poland</option>
						<option value="PT" >Portugal</option>
						<option value="PR" >Puerto Rico</option>
						<option value="QA" >Qatar</option>
						<option value="RE" >Reunion</option>
						<option value="RO" >Romania</option>
						<option value="RU" >Russia</option>
						<option value="RW" >Rwanda</option>
						<option value="KN" >Saint Kitts and Nevis</option>
						<option value="LC" >Saint Lucia</option>
						<option value="VC" >Saint Vincent and the Grenadines</option>
						<option value="WS" >Samoa</option>
						<option value="SM" >San Marino</option>
						<option value="ST" >Sao Tome and Principe</option>
						<option value="SA" >Saudi Arabia</option>
						<option value="SN" >Senegal</option>
						<option value="CS" >Serbia and Montenegro</option>
						<option value="SC" >Seychelles</option>
						<option value="SL" >Sierra Leone</option>
						<option value="SG" >Singapore</option>
						<option value="SK" >Slovakia</option>
						<option value="SI" >Slovenia</option>
						<option value="SB" >Solomon Islands</option>
						<option value="SO" >Somalia</option>
						<option value="ZA" >South Africa</option>
						<option value="GS" >South Georgia and the South Sandwich Islands</option>
						<option value="KR" >South Korea</option>
						<option value="ES" >Spain</option>
						<option value="LK" >Sri Lanka</option>
						<option value="SH" >St. Helena</option>
						<option value="PM" >St. Pierre and Miquelon</option>
						<option value="SD" >Sudan</option>
						<option value="SR" >Suriname</option>
						<option value="SJ" >Svalbard</option>
						<option value="SZ" >Swaziland</option>
						<option value="SE" >Sweden</option>
						<option value="CH" >Switzerland</option>
						<option value="SY" >Syria</option>
						<option value="TW" >Taiwan</option>
						<option value="TJ" >Tajikistan</option>
						<option value="TZ" >Tanzania</option>
						<option value="TH" >Thailand</option>
						<option value="TG" >Togo</option>
						<option value="TK" >Tokelau</option>
						<option value="TO" >Tonga</option>
						<option value="TT" >Trinidad and Tobago</option>
						<option value="TN" >Tunisia</option>
						<option value="TR" >Turkey</option>
						<option value="TM" >Turkmenistan</option>
						<option value="TC" >Turks and Caicos Islands</option>
						<option value="TV" >Tuvalu</option>
						<option value="UG" >Uganda</option>
						<option value="UA" >Ukraine</option>
						<option value="AE" >United Arab Emirates</option>
						<option value="GB" >United Kingdom</option>
						<option value="VI" >United States Virgin Islands</option>
						<option value="UY" >Uruguay</option>
						<option value="UZ" >Uzbekistan</option>
						<option value="VU" >Vanuatu</option>
						<option value="VE" >Venezuela</option>
						<option value="VN" >Vietnam</option>
						<option value="WF" >Wallis and Futuna</option>
						<option value="PS" >West Bank</option>
						<option value="EH" >Western Sahara</option>
						<option value="YE" >Yemen</option>
						<option value="ZM" >Zambia</option>
						<option value="ZW" >Zimbabwe</option>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>

			<tr>
				<td class="formLabel">	<nobr>Postal Code:</nobr>
</td>
				<td class="formFieldSmall" colspan="2">
					<input tabindex="6" name="postal_code" type="text" maxlength="11" value="" />
					<br />
					<span class="smallText">Required for US, UK & Canada Only</span>
				</td>
				<td>&nbsp;</td>
			</tr>
			
			<!--Begin Display of Comedy Style If Comedian Account-->
			<?php if (($_GET['signup_type'] == "m") || ($_GET['signup_type'] == "c")) { ?>
			<tr>
				
				<td class="formLabel">	<nobr><?php if ($_GET['signup_type'] == "m") { ?>Genre<?php } elseif ($_GET['signup_type'] == "c") { ?>Comedy Style<? } ?>:</nobr>
</td>
				
				<td class="formFieldSmall" colspan="2">
					<select name="genre">
						<option value="" selected>---</option>
						<?php if ($_GET['signup_type'] == "m") { ?>
						<option value="ACP" >A capella</option>
						<option value="ACO" >Acoustic</option>
						<option value="ALC" >Alt Country</option>
						<option value="ALT" >Alternative</option>
						<option value="AME" >Americana</option>
						<option value="ART" >Art Rock</option>
						<option value="BLG" >Bluegrass</option>
						<option value="BLU" >Blues</option>
						<option value="BPO" >Brit Pop</option>
						<option value="CEL" >Celtic</option>
						<option value="CHR" >Christian</option>
						<option value="CRK" >Christian Rock</option>
						<option value="CRA" >Christian Rap</option>
						<option value="CLA" >Classical</option>
						<option value="COU" >Country</option>
						<option value="CRU" >Crunk</option>
						<option value="DAN" >Dance</option>
						<option value="DIS" >Disco</option>
						<option value="ELE" >Electronica</option>
						<option value="EPO" >Electropop</option>
						<option value="EMO" >Emo</option>
						<option value="EXP" >Experimental</option>
						<option value="FLK" >Folk</option>
						<option value="FLR" >Folk-Rock</option>
						<option value="FRE" >Freestyle</option>
						<option value="FNK" >Funk</option>
						<option value="GRK" >Garage Rock</option>
						<option value="GLM" >Glam</option>
						<option value="GOS" >Gospel</option>
						<option value="GTH" >Goth</option>
						<option value="GRU" >Grunge</option>
						<option value="HRK" >Hard Rock</option>
						<option value="HIP" >Hip-Hop</option>
						<option value="HOU" >House</option>
						<option value="IND" >Indie Rock</option>
						<option value="INL" >Industrial</option>
						<option value="JRK" >Jam Rock</option>
						<option value="JUN" >Jungle</option>
						<option value="JAZ" >Jazz</option>
						<option value="LAT" >Latin</option>
						<option value="LAP" >Latin Pop</option>
						<option value="MAR" >Mariachi</option>
						<option value="MET" >Metal</option>
						<option value="MOT" >Motown</option>
						<option value="OSR" >Old School Rap</option>
						<option value="POP" >Pop</option>
						<option value="PWP" >Power Pop</option>
						<option value="PRK" >Progressive Rock</option>
						<option value="PSY" >Psychedelic</option>
						<option value="PSB" >Psychobilly</option>
						<option value="PNK" >Punk</option>
						<option value="RAB" >R&B</option>
						<option value="RAP" >Rap</option>
						<option value="REG" >Reggae</option>
						<option value="RET" >Retro</option>
						<option value="ROC" >Rock</option>
						<option value="RCK" >Rockabilly</option>
						<option value="ROO" >Roots</option>
						<option value="SAL" >Salsa</option>
						<option value="SIN" >Singer-Songwrite</option>
						<option value="SKA" >Ska</option>
						<option value="SOU" >Soul</option>
						<option value="SRP" >Southern Rap</option>
						<option value="STB" >String Bands</option>
						<option value="SRF" >Surf</option>
						<option value="TNG" >Tango</option>
						<option value="TCH" >Techno</option>
						<option value="TRN" >Trance</option>
						<option value="TRH" >Trip Hop</option>
						<option value="TUN" >Turntablist</option>
						<option value="WRD" >World</option>
						<?php } elseif ($_GET['signup_type'] == "c") { ?>
						<option value="ASI" >Asian</option>
						<option value="BLA" >Black</option>
						<option value="BLU" >Blue Collar</option>
						<option value="CEL" >Celebrity Humor</option>
						<option value="CLO" >Clown</option>
						<option value="HYP" >Hypnotist</option>
						<option value="GAY" >Gay/Lesbian</option>
						<option value="IMP" >Impersonations</option>
						<option value="IPV" >Improv</option>
						<option value="LAT" >Latino</option>
						<option value="MAG" >Magic</option>
						<option value="MUS" >Musical</option>
						<option value="PAR" >Parody</option>
						<option value="POL" >Political</option>
						<option value="SKE" >Sketch</option>
						<option value="STA" >Stand-Up</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<!--End Display of Comedy Style If Comedian Account-->
			<? } ?>
			<?php if ($_GET['signup_type'] == "m") { ?>
			
			<tr>
				<td class="formLabel">	<nobr>Record Label:</nobr>
</td>
				<td class="formFieldSmall" colspan="2">
					<input tabindex="9" name="record_label" type="text" maxlength="50" value="" />
				</td>
			</tr>

			<tr>
				<td class="formLabel">	<nobr>Label Type:</nobr>
</td>
				<td class="formFieldSmall" colspan="2">
					<select tabindex="10" name="label_type">
						<option value="" selected>---</option>
						<option value="IN" >Independent</option>
						<option value="UN" >Unsigned</option>
						<option value="ML" >Major Label</option>
					</select>
				</td>
			</tr>
			<? } ?>
			<?php if ($_GET['signup_type'] == "m") { ?>
			<input type="hidden" name="gender" value="m">
			<? } else { ?>
			
			<tr>
				<td class="formLabel">	<nobr>Gender:</nobr>
</td>
				<td class="formFieldSmall" colspan="2">
					<input tabindex="11" name="gender" type="radio" value="m" > Male
					&nbsp;
					<input tabindex="12" name="gender" type="radio" value="f" > Female
				</td>
			</tr>
			<? } ?>

			<tr>
				<td class="formLabel">	<nobr>Date of Birth:</nobr>
</td>
				<td class="formFieldSmall" colspan="2">
					<select name="birthday_mon" tabindex="13">
						<option value="---">---</option>
							<option value="1" > Jan  </option>
							<option value="2" > Feb  </option>
							<option value="3" > Mar  </option>
							<option value="4" > Apr  </option>
							<option value="5" > May  </option>
							<option value="6" > Jun  </option>
							<option value="7" > Jul  </option>
							<option value="8" > Aug  </option>
							<option value="9" > Sep  </option>
							<option value="10" > Oct  </option>
							<option value="11" > Nov  </option>
							<option value="12" > Dec  </option>
					</select>
			
					<select name="birthday_day" tabindex="14">
						<option value="---">---</option>
								<option >1</option>
								<option >2</option>
								<option >3</option>
								<option >4</option>
								<option >5</option>
								<option >6</option>
								<option >7</option>
								<option >8</option>
								<option >9</option>
								<option >10</option>
								<option >11</option>
								<option >12</option>
								<option >13</option>
								<option >14</option>
								<option >15</option>
								<option >16</option>
								<option >17</option>
								<option >18</option>
								<option >19</option>
								<option >20</option>
								<option >21</option>
								<option >22</option>
								<option >23</option>
								<option >24</option>
								<option >25</option>
								<option >26</option>
								<option >27</option>
								<option >28</option>
								<option >29</option>
								<option >30</option>
								<option >31</option>
					</select>					
					<select name="birthday_yr" tabindex="15">
						<option value="---">---</option>
									
											<option >2023</option>
											<option >2022</option>
											<option >2021</option>
											<option >2020</option>
											<option >2019</option>
											<option >2018</option>
											<option >2017</option>
											<option >2016</option>
											<option >2015</option>
											<option >2014</option>
											<option >2013</option>
											<option >2012</option>
											<option >2011</option>
											<option >2010</option>
											<option >2009</option>
											<option >2008</option>
											<option >2007</option>
											<option >2006</option>
											<option >2005</option>
											<option >2004</option>
											<option >2003</option>
											<option >2002</option>
											<option >2001</option>
											<option >2000</option>
											<option >1999</option>
											<option >1998</option>
											<option >1997</option>
											<option >1996</option>
											<option >1995</option>
											<option >1994</option>
											<option >1993</option>
											<option >1992</option>
											<option >1991</option>
											<option >1990</option>
											<option >1989</option>
											<option >1988</option>
											<option >1987</option>
											<option >1986</option>
											<option >1985</option>
											<option >1984</option>
											<option >1983</option>
											<option >1982</option>
											<option >1981</option>
											<option >1980</option>
											<option >1979</option>
											<option >1978</option>
											<option >1977</option>
											<option >1976</option>
											<option >1975</option>
											<option >1974</option>
											<option >1973</option>
											<option >1972</option>
											<option >1971</option>
											<option >1970</option>
											<option >1969</option>
											<option >1968</option>
											<option >1967</option>
											<option >1966</option>
											<option >1965</option>
											<option >1964</option>
											<option >1963</option>
											<option >1962</option>
											<option >1961</option>
											<option >1960</option>
											<option >1959</option>
											<option >1958</option>
											<option >1957</option>
											<option >1956</option>
											<option >1955</option>
											<option >1954</option>
											<option >1953</option>
											<option >1952</option>
											<option >1951</option>
											<option >1950</option>
											<option >1949</option>
											<option >1948</option>
											<option >1947</option>
											<option >1946</option>
											<option >1945</option>
											<option >1944</option>
											<option >1943</option>
											<option >1942</option>
											<option >1941</option>
											<option >1940</option>
											<option >1939</option>
											<option >1938</option>
											<option >1937</option>
											<option >1936</option>
											<option >1935</option>
											<option >1934</option>
											<option >1933</option>
											<option >1932</option>
											<option >1931</option>
											<option >1930</option>
											<option >1929</option>
											<option >1928</option>
											<option >1927</option>
											<option >1926</option>
											<option >1925</option>
											<option >1924</option>
											<option >1923</option>
											<option >1922</option>
											<option >1921</option>
											<option >1920</option>
											<option >1919</option>
											<option >1918</option>
											<option >1917</option>
											<option >1916</option>
											<option >1915</option>
											<option >1914</option>
											<option >1913</option>
											<option >1912</option>
											<option >1911</option>
											<option >1910</option>
											<option >1909</option>
											<option >1908</option>
											<option >1907</option>
											<option >1906</option>
											<option >1905</option>
											<option >1904</option>
											<option >1903</option>
											<option >1902</option>
											<option >1901</option>
											<option >1900</option>
					</select>
					<?php if ($_GET['signup_type'] == "m") { ?>
					<br />
					<span class="smallText">The birth date of the account holder.</span>
					<? } ?>
				</td>
			</tr>
                        <tr>                                
				<td class="formLabel" valign="top"><div id="verificationLabel" name="verificationLabel" style="margin-top:5px;">Verification:</div></td>
                                <td class="formFieldSmall" colspan="2">
                                      <div id="verificationField" name="verificationField" style="float:left;">
						<input size="20" tabindex="16" name="response" maxlength="5" value="" type="text">&nbsp;&nbsp;<br /><span class="smallText">Enter the text in the image &nbsp;</span></div>
                                        <div id="verificationImage" name="verificationImage" style="float:left;margin-left:1px;"><a href="#" onClick="document.verificationImg.src='/cimg?c=<? echo $captchaid; ?>&'+Math.random();return false"><img name="verificationImg" src="/cimg?c=<? echo $captchaid; ?>" align="texttop" border="0"></a> 
						<div class="smallText" style="text-align:center;">
							<a href="#" onClick="document.verificationImg.src='/cimg?c=<? echo $captchaid; ?>&'+Math.random();return false">Can't read?</a>
						</div>
					</div>
                                        <input type=hidden name=challenge value="<? echo $captchaid; ?>">
	                        </td>
                        </tr>
			<tr>
				
				<td class="formFieldSmall"> &nbsp;</td>
				<td class="formFieldSmall" colspan="2">
					<br /><input tabindex="17" type="checkbox" name="weekly_tube" CHECKED value="checkbox">&nbsp;Sign me up for the "Broadcast Yourself" email			
					<br />- I agree to the <a href="/t/terms" target="_blank">terms of use</a> and <a href="/t/privacy" target="_blank">privacy policy</a>.
					<p><input tabindex="18" name="action_signup" type="submit" value="Sign Up"></p>	
				</td>
			</tr>
		</table>
	</form>
</div>
		
<div id="suSigninDiv">
	<h2>Log In</h2>
	<p>Already a Member? Login here.</p>
	
	<form method="post" name="loginForm" id="loginForm">
		<input type="hidden" name="current_form" value="loginForm" />
			
		
	
		
			<?php if($_GET['next'] != NULL) { ?><input type="hidden" name="next" value="<?php echo htmlspecialchars($_GET["next"]); ?>" /><? } ?>
		
	
		
	
		
	
		
	
		
	

		<table class="dataEntryTableSmall">
			<tr>
				<td class="formLabel">	<nobr>User Name:</nobr>
</td>
				<td class="formFieldSmall"><input tabindex="101" type="text" size="20" name="username" value=""></td>
			</tr>
			<tr>
				<td class="formLabel">	<nobr>Password:</nobr>
</td>
				<td class="formFieldSmall"><input tabindex="102" type="password" size="20" name="password"></td>
			</tr>	
			<tr>
				<td class="formLabel">&nbsp;</td>
				<td class="formFieldSmall"><input tabindex="103" type="submit" name="action_login" value="Log In">
				<p class="smallText"><b>Forgot:</b>&nbsp;<a href="forgot_username">Username</a> | <a href="/forgot">Password</a></p>
				</td>
			</tr>
		</table>
	</form>
	<br />
	<h2>What Is YouTube?</h2>
	<p>YouTube is a way to get your videos to the people who matter to you.<br>
	With YouTube you can:</p>
	<ul>			
		<li>Upload, tag and share your videos worldwide</li>
		<li>Browse thousands of original videos uploaded by community members</li>
		<li>Find, join and create video groups to connect with people with similar interests</li>
		<li>Customize your experience with playlists and subscriptions</li>
		<li>Integrate YouTube with your website using video embeds or APIs</li>
	</ul>
	<p>To learn more about our service, please see the <a href="/t/help_center">Help Center</a>.</p>
</div>

<script type="text/javascript">

	document.signupForm.email.focus();

</script>


<?php 
require "needed/end.php";
?>