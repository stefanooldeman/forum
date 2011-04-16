<?php
$username = '';
$loginmessage = null;
$errors = array();
if(isset($_POST['login'])) {
	// Form has been submitted.
	$errors = array_merge($errors, check_required_fields(array('username', 'password'), $_POST));
	$errors = array_merge($errors, check_max_field_lengths(array('username' => 30, 'password' => 30), $_POST));

	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$passwordRaw = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
	$password = hash('sha256', SYS_SALT . $passwordRaw);
	unset($passwordRaw);

	if(is_array($errors) && count($errors) == 0) {
		// Check database to see if username and the hashed password exist there.
		$query = 'SELECT u.id, u.username
			FROM users u
			WHERE u.username = "' . $username . '" AND password = "' . $password . '"
			LIMIT 1';

		$result_set = mysql_query($query);
		confirm_query($result_set);

		if(mysql_num_rows($result_set) == 1) {
			// username/password authenticated
			// and only 1 match
			$found_user = mysql_fetch_array($result_set);
			$_SESSION['user_id'] = $found_user['id'];
			$_SESSION['username'] = $found_user['username'];
		} else {
			// username/password combo was not found in the database
			$loginmessage = " I'm sorry this combination won't work for you, perhaps your CapsLock is on?";
		}

	} else {

		if(count($errors) == 1) {
			$loginmessage = " Sorry, you really need to fill in 2 fields!";
		} else {
			$loginmessage = "WTF? Why are you poking in the air";
		}
	}
	unset($_POST);
	//prevent Form Resubmission
}
if(!isset($_POST['login']) || (is_array($errors) && count($errors) > 0) || $loginmessage != null) {
	$password = '';
}

$output .= "
<div id='logo'><img src='" . MEDIA_URL . "images/audicious.jpg' alt='audicious.com' /></div>
<div id='sidebar'>
<div id='navuserlogin'>";
if(!isset($_SESSION['user_id'])){
	if (isset($loginmessage)){$output .= "<div class='white' style='margin:1px;;width:177px;height:55px;'>". $loginmessage ."</div>";} else{$output .= "<img src='" . MEDIA_URL . "images/notamember.jpg' />";}
	$output .= "<form action='' method='post'>
	<div class='white tenpx' style='margin-bottom:5px;'>
	U: <input tabindex='1' name='username' maxlength='30' value='". htmlentities($username) ."' class='navlogininput' type='text' />
	&nbsp;&nbsp;<input tabindex='3' value='Go For It!' type='submit' name='login' />
	</div>
	<div class='white tenpx'>
	P: <input tabindex='2' name='password' value='". htmlentities($password) ."' class='navlogininput' type='password' />
	&nbsp;&nbsp;<a href='#' class='greya ninepx'>Forgot it?</a>
	</div>
	</form>";
} else {
	$user_id = $_SESSION['user_id'];
	$query = "SELECT id FROM inbox WHERE readed = 0 AND user_id = $user_id";
	$read_set = mysql_query($query, $connection);
	confirm_query($read_set);
	$numreaded = mysql_num_rows($read_set);

	$output .= "<h2 class='white'>Ahoy, <a href='" . BASE_URL . "user/profile/". $_SESSION['user_id'] ."'>". $_SESSION['username'] ."</a>!</h2>";
	$output .= "<ul>\n<li><a href='" . BASE_URL . "logout' class='tenpx offlinea'>sorry, i'm leaving!</a></li>";

	if($numreaded == 1) {
		$output .= "<li><a href='" . BASE_URL . "inbox' class='darkgreena'>1 New Message</a></li>";
	} elseif($numreaded > 1) {
		$output .= "<li><a href='" . BASE_URL . "inbox' class='darkgreena'>".$numreaded." New Messages</a></li>";
	} else {
		$output .= "<li><a href='" . BASE_URL . "inbox' class='darkgreena'>No Messages</a></li>";
	}

	$output .= "</ul>\n";
}

$output .= "</div>";

$output .= "<div id='threadnav'>
	<img src='" . MEDIA_URL . "images/h1.threads.png' width='198' usemap='#threads' />
	<map name='threads' id='threads'><area shape='rect' coords='0,0,198,20' href='" . BASE_URL . "threads' alt='threads' /></map>
	<div class='browndot'/><span></span></div>
	<ul>
		<li><a href='" . BASE_URL . "threads/discussions' class='greena'>DISCUSSIONS</a></li>
		<li><a href='" . BASE_URL . "threads/projects' class='greena'>PROJECTS</a></li>
		<li><a href='" . BASE_URL . "threads/advice' class='greena'>ADVICE</a></li>
		<li><a href='" . BASE_URL . "threads/meaningless' class='greena'>MEANINGLESS</a></li>
	</ul>
</div>";

if(isset($_SESSION['user_id'])){
 $output .= "<div id='usernav'>
		<img src='" . MEDIA_URL . "images/h1.hometown.png' width='198' usemap='#hometown' />
		<map name='hometown' id='hometown'><area shape='rect' coords='0,0,198,20' href='" . BASE_URL . "user/profile/" . $_SESSION['user_id'] . "' alt='profile page' /></map>
       	<div class='browndot'/><span></span></div>
        <ul>
            <li><a href='" . BASE_URL . "user/profile/" . $_SESSION['user_id'] . "' class='darkgreena'>My acount</a></li>
            <li><a href='" . BASE_URL . "inbox' class='darkgreena'>Inbox</a></li>
            <li><a href='' class='darkgreena'>Preferences</a></li>
			<li><a href='" . BASE_URL . "thread/new' class='darkgreena'>Post Thread</a></li>
            <li><a href='" . BASE_URL . "user/invites' class='darkgreena'>Invites</a></li>
		</ul>
    </div>
    <div id='maytiesinfo'>
      <img src='" . MEDIA_URL . "images/h1.mayties.png' width='198' border='0px' />
       	<div class='browndot'/><span></span></div>
		<p class='white elevenpx'>
            You a.k.a. <strong><a href='#' class='user'>cobus300</a></strong> have 12 mayties. 7 of those are gurls and
            the other 5 are guys. There are  <span class='onlinea'>3</span> maytes <span class='onlinea'>online</span>
        </p>
	</div>
    <div id='maytieslist'>
        <a href='#' class='offlinea'>mieka</a><span class='sepie elevenpx'>|</span><a href='#' class='offlinea'>johny</a><span class=
        'sepie elevenpx'>|</span><a href='#' class='onlinea'>cobus300</a>
        </div>";
}
$output .= "</div>";


