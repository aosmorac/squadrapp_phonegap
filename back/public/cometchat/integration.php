<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* ADVANCED */

define('SET_SESSION_NAME','');			// Session name
define('DO_NOT_START_SESSION','0');		// Set to 1 if you have already started the session
define('DO_NOT_DESTROY_SESSION','0');	// Set to 1 if you do not want to destroy session on logout
define('SWITCH_ENABLED','0');		
define('INCLUDE_JQUERY','1');	
define('FORCE_MAGIC_QUOTES','0');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */



// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW

define('DB_SERVER',					"squadrapp.che4o6g7u1a8.us-east-1.rds.amazonaws.com"								); // Site's Database Host.
define('DB_PORT',					"3306"									); // Site's Database Port.
define('DB_USERNAME',				"squadrapp"									); // Site's Database User.
define('DB_PASSWORD',				"Hubbog123"								); // Site's Database Password.
define('DB_NAME',					"squadrapp"							); // Site's Database Name.
define('TABLE_PREFIX',				""										); // Site's Table Prefix.
define('DB_USERTABLE',				"user"									); // Site's Users table.
define('DB_USERTABLE_USERID',		"id_user"								); // Numeric primary or unique key of Users table.
define('DB_USERTABLE_NAME',			"use_name"								); // Display Name from users table.
define('DB_AVATARTABLE',		    " "										); // If profile pictures are stored in the different table then Join clause with this table table(eg. " left join avatars on avatar.userid = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." ") else leave it empty.
define('DB_AVATARFIELD',		    " ".TABLE_PREFIX.DB_USERTABLE.".Facebook_id "); // Field that stores profile picture
define('DB_USERTABLE_LASTACTIVITY',	"lastactivity"							); // Field that stores last active time for a user if you have any such field then modify the constant DB_USERTABLE_LASTACTIVITY and set ADD_LAST_ACTIVITY to 0.
define('ADD_LAST_ACTIVITY',			"1"										); // Set this field to 0 If users table alredy has the field to store last active time of a user.

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* FUNCTIONS */

function getUserID() {
	$userid = 0;	
	if (!empty($_SESSION['basedata']) && $_SESSION['basedata'] != 'null') {
		$_REQUEST['basedata'] = $_SESSION['basedata'];
	}
	
	if (!empty($_REQUEST['basedata'])) {	
		if (function_exists('mcrypt_encrypt')) {
			$key = KEY_A.KEY_B.KEY_C;
			$uid = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($_REQUEST['basedata']), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
			if (intval($uid) > 0) {
				$userid = $uid;
			}
		} else {
			$userid = $_REQUEST['basedata'];
		}
	}
	if (!empty($_SESSION['userid'])) {
		$userid = $_SESSION['userid'];
	}
	
	$userid = intval($userid);
	return $userid;
}

function chatLogin($userName,$userPass) {
	$userid = 0;
	if (filter_var($userName, FILTER_VALIDATE_EMAIL)) {
		$sql = ("SELECT * FROM `".TABLE_PREFIX.DB_USERTABLE."` WHERE email ='".$userName."'");
	} else {
		$sql = ("SELECT * FROM `".TABLE_PREFIX.DB_USERTABLE."` WHERE username ='".$userName."'"); 
	}
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$salted_password = md5($row1['value'].$userPass.$row['salt']);
	if ($row['password'] == $salted_password) {
		$userid = $row['user_id'];
	}
	if ($userid && function_exists('mcrypt_encrypt')) {
		$key = KEY_A.KEY_B.KEY_C;
		$userid = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $userid, MCRYPT_MODE_CBC, md5(md5($key))));
	}

	return $userid;
}

function getFriendsList($userid,$time) {	
	$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity, ".DB_AVATARFIELD." avatar, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX."friends join ".TABLE_PREFIX.DB_USERTABLE." on  ".TABLE_PREFIX."friends.toid = ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX."friends.fromid = '".mysql_real_escape_string($userid)."' order by username asc");
	if ((defined('MEMCACHE') && MEMCACHE <> 0) || DISPLAY_ALL_USERS == 1) {		
		$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity, " .DB_AVATARFIELD. " avatar, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ('".mysql_real_escape_string($time)."'-".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." < '".((ONLINE_TIMEOUT)*2)."') and (cometchat_status.status IS NULL OR cometchat_status.status <> 'invisible' OR cometchat_status.status <> 'offline') order by username asc");		
	}
	return $sql;
}

function getFriendsIds($userid) {
	$sql = ("select group_concat(friends.myfrndids) myfrndids from (SELECT toid as myfrndids FROM `friends` WHERE status =1 and fromid = '".mysql_real_escape_string($userid)."' union SELECT fromid as myfrndids FROM `friends` WHERE status =1 and toid = '".mysql_real_escape_string($userid)."') friends ");

	return $sql;
}

function getUserDetails($userid) {
	$sql = ("select ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_LASTACTIVITY." lastactivity,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." link, ".DB_AVATARFIELD." avatar, cometchat_status.message, cometchat_status.status from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	
	return $sql;
}

function updateLastActivity($userid) {
	$sql = ("update `".TABLE_PREFIX.DB_USERTABLE."` set ".DB_USERTABLE_LASTACTIVITY." = '".getTimeStamp()."' where ".DB_USERTABLE_USERID." = '".mysql_real_escape_string($userid)."'");
	
	return $sql;
}

function getUserStatus($userid) {
	$sql = ("select cometchat_status.message, cometchat_status.status from cometchat_status where userid = '".mysql_real_escape_string($userid)."'");
	
	return $sql;
}

function fetchLink($link) {
    //return BASE_URL.'../users.php?id='.$link;
	return '';
}

function getAvatar($image) {
//    if (is_file(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$image.'.gif')) {
//        return 'images/'.$image.'.gif';
//    } else {
//        return BASE_URL.'images/noavatar.gif';
//    }
    return "https://graph.facebook.com/{$image}/picture?width=120&height=120";
}

function getTimeStamp() {
	return time();
}

function processTime($time) {
	return $time;
}

if (!function_exists('getLink')) {
  	function getLink($userid) { 
		return fetchLink($userid); 
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* HOOKS */

function hooks_statusupdate($userid,$statusmessage) {
	
}

function hooks_forcefriends() {
	
}

function hooks_activityupdate($userid,$status) {

}

function hooks_message($userid,$to,$unsanitizedmessage) {
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LICENSE */

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'license.php');
$x="\x62a\x73\x656\x34\x5fd\x65c\157\144\x65";
eval($x('JHI9ZXhwbG9kZSgnLScsJGxpY2Vuc2VrZXkpOyRwXz0wO2lmKCFlbXB0eSgkclsyXSkpJHBfPWludHZhbChwcmVnX3JlcGxhY2UoIi9bXjAtOV0vIiwnJywkclsyXSkpOw'));

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
