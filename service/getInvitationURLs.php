<?php
header('Content-type: application/json');


class JWInviteSet {
	
	public $invitationArr = array('googleURL' =>'', 'outlookURL' => '', 'iCalURL' => '');
	public $filepath = "invitation_files/"; //change path to .ics & .vcs files here, if necessary
	
	//builds array of three URLs - invitations in Google Calendar, iCal and Outlook formats
	function __construct($eventId, $datetime, $location, $subject, $description = '') {
		
		//format location, subject and description for use in URL query string
		$urlLocation = str_replace("\n", "", $location);
		$urlLocation = urlencode($urlLocation);
		$urlSubject = str_replace("\n", "", $subject);
		$urlSubject = urlencode($urlSubject);
		$urlDescription = urlencode($description);
		
		$googleStr = "http://www.google.com/calendar/event?action=TEMPLATE&amp;location=".$urlLocation."&amp;dates=".$datetime."%2F".$datetime."&amp;sprop=website%3Awww.paperlesspost.com&amp;details=".$urlDescription."&amp;text=".$urlSubject;
		$this->invitationArr['googleURL'] = $googleStr;
		
		//format location and description for writing to file (escape existing new lines)
		$location = str_replace("\n", "", $location);
		$description = str_replace("\n", "\\n", $description);
		
		//if the .vcs file for this event ID does not exist, build it
		if(!file_exists('../../'.$this->filepath.$eventId.".vcs")) {
			$newVcsFile = fopen('../../'.$this->filepath.$eventId.".vcs", 'w') or die("Can't open file");
			$vcsString = "BEGIN:VCALENDAR\nPRODID:-//Paperless Post//paperlesspost.com//EN\nVERSION:1.0\nBEGIN:VEVENT\nDTSTART:".$datetime."\n    DTEND:".$datetime."\nSUMMARY:".$subject." \nDESCRIPTION:\n ".$description." \nLOCATION:".$location." \nEND:VEVENT\nEND:VCALENDAR\n";
			 fwrite($newVcsFile,$vcsString);
			 fclose($newVcsFile);
		}
		$this->invitationArr['outlookURL'] = $this->filepath.$eventId.".vcs";
		
		//if the .ics file for this event ID does not exist, build it
		if(!file_exists('../../'.$this->filepath.$eventId.".ics")) {
			$newIcsFile = fopen('../../'.$this->filepath.$eventId.".ics", 'w') or die("Can't open file");
			$icsString = "BEGIN:VCALENDAR\nPRODID:-//Paperless Post//paperlesspost.com//EN\nVERSION:2.0\nCALSCALE:GREGORIAN\nBEGIN:VEVENT\nDTSTART:".$datetime."\nDTEND:".$datetime."\nDTSTAMP:20100923T143318Z\nORGANIZER;CN=Nathaniel:MAILTO:nathaniel@humanplayfulness.com\nUID:303986-c8b17563@paperlesspost.com\nCLASS:PRIVATE\nCREATED::".date('Ymd')."T".date('His')."Z\nSUMMARY:".$subject." \nDESCRIPTION:".$description." \nLAST-MODIFIED:".date('Ymd')."T".date('His')."Z\nLOCATION:".$location."\nSEQUENCE:0\nSTATUS:TENTATIVE\nTRANSP:OPAQUE\nEND:VEVENT\nEND:VCALENDAR\n";
			 fwrite($newIcsFile,$icsString);
			 fclose($newIcsFile);
		}
		$this->invitationArr['iCalURL'] = $this->filepath.$eventId.".ics";
	}
	
	function getInviteUrls(){
		return $this->invitationArr;
	}	
}

$set = new JWInviteSet($_POST['eventId'], $_POST['datetime'],$_POST['location'], $_POST['subject'],$_POST['description']);
$inviteArr = $set->getInviteUrls();
echo json_encode($inviteArr,JSON_ESCAPED_SLASHES);
?>