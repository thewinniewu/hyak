<?php

define ('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/includes/lib/php-snapchat/src/snapchat.php');

$snapchat = new Snapchat('harvardyak', 'h@rv@rdy4k!');

$snapchat->updatePrivacy(Snapchat::PRIVACY_FRIENDS);

$snaps = $snapchat->getSnaps();

$added_friends = $snapchat->getAddedFriends();

foreach($added_friends as $added_friend) {
    $snapchat->addFriend($added_friend->name);
}

foreach ($snaps as $snap) {
    var_dump($snap); 
    echo "<br/>";
    $typearray = array(
        Snapchat::MEDIA_VIDEO => '.mov',
        Snapchat::MEDIA_IMAGE => '.jpg'
    ); 
    
    // download the data
    $data = $snapchat->getMedia($snap->id);
         
    $filename = __ROOT__ .
        '/includes/downloads/'.
        $snap->id.
        $typearray[$snap->media_type]; 
    
    file_put_contents($filename, $data); 

    // mark snap as viewed
    //$snapchat->markSnapViewed($snap->id);

    //  upload as snap
    $id = $snapchat->upload(
       $snap->media_type, 
       file_get_contents($filename)
    );
   
    // delete the files
    // unlink($filename);

     // add to story   
     $snapchat->setStory($id, $snap->media_type);

    // for terminal testing
    echo "<br/>done";
}

// destroy evidence
$snapchat->clearFeed();

echo "<br/>refresh complete";