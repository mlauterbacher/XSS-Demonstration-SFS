<?php

#######
# includes and settings
#######

date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL);
require_once 'guestbook.php';
require_once 'gbentry.php';
session_start();

#######
# request treatment
#######

$gb = new Guestbook('gb.txt');
$message = '';

switch(@$_POST['action']) {
	case 'new':
		$ne = new GbEntry();
		$ne->setSender(@$_POST['sender'])
		   ->setText(@$_POST['text']);
		$gb->addEntry($ne)->save();
		break;
	case 'clean':
        if (@$_SESSION['auth'] === true) {
            $message = 'All entries deleted.';
            $gb->clean();
        } else {
            $message = 'dafuq?';
        }
		break;

    case 'login':
        if (@$_POST['password'] == 'demo') {
            $_SESSION['auth'] = true;
            $_SESSION['username'] = @$_POST['username'];
            $message = 'Logged in as ' . @$_POST['username'] . '.';
        } else {
            $message = 'Login failed.';
        }
        break;

    case 'logout':
        $message = 'Bye.';
        session_destroy();
        session_start();
        break;
}

########
# build html page
########

ob_start();

echo '<div id="entries">' . "\n";
$allEntries = array_reverse($gb->getEntries());
if (!empty($allEntries)) {
	foreach($allEntries as $i => $e) {
		echo "<div id=\"entry$i\">" . "\n";
		
		echo '	<span class="sender">' 
			. stripslashes($e->getSender())
			. '</span>' . "\n";
		echo '	<span class="time">'   
			. date("d.m.Y G:i:s", $e->getTimestamp()) 
			. '</span>' . "\n";
		echo '	<span class="text">'
			. stripslashes($e->getText() )
			. '</span>' . "\n";
			
		echo '</div>' . "\n";
	}
} else {
	echo "There are no entries.";
}
echo '</div>' . "\n";

$tpl = file_get_contents('template_index.html');

$tpl = str_replace("{{entries}}", ob_get_clean(), $tpl);
$tpl = str_replace("{{form}}", file_get_contents('form.html'), $tpl);

if (@$_SESSION['auth'] === true) {
    $usercp = file_get_contents('admin.html');
} else {
    $usercp = file_get_contents('anonymous.html');
}

if (!empty($message)) {
    $message = '<p id="message" class="box">' . $message . '</p>';
}

$tpl = str_replace("{{usercp}}", $usercp, $tpl);
$tpl = str_replace("{{message}}", $message, $tpl);
$tpl = str_replace("{{username}}", @$_SESSION['username'], $tpl);

echo $tpl;
