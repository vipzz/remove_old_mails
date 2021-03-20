<?php
$olderthan = 5;
$del = new DateTime();
$del->modify('-'.$olderthan.' minutes');

echo "Deleting emails older then $olderthan \n";

$mbox = imap_open("{imap.example.com:993/imap/ssl}INBOX", "sample@example.com", "password") or die("can't connect: " . imap_last_error());
echo "Connected\n";
$MC = imap_check($mbox);

// Fetch an overview for all messages in INBOX
$result = imap_fetch_overview($mbox,"1:{$MC->Nmsgs}",0);

$delctr = 0;

if(sizeof($result) > 0){
  foreach ($result as $overview) {
    $date = $overview->date;
    $date = DateTime::createFromFormat('D, d M Y H:i:s O', $date); 

    if($date<$del) {
        imap_delete($mbox,$overview->msgno);
        $delctr++;
    }
  }
}
imap_expunge($mbox);
imap_close($mbox);

echo "$delctr emails deleted\n";
?>

