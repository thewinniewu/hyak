<?php

define ('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/includes/lib/php-snapchat/src/snapchat.php');


// usernames and passwords
// should be replaced with db eventually

$snapgroups = array(
    array(
        "name" => "harvardyak",
        "pw" => "h@rv@rdy4k!"
    ),
    array(
        "name" => "hackatbrown",
        "pw" => "h@ckatbr0wn",
    ),
);
 
foreach ($snapgroups as $snapgroup) {
    $snapchat = new Snapchat($snapgroup["name"], $snapgroup["pw"]);

    $snapchat->updatePrivacy(Snapchat::PRIVACY_FRIENDS);

    $added_friends = $snapchat->getAddedFriends();

    foreach($added_friends as $added_friend) {
        $snapchat->addFriend($added_friend->name);
    }

    $snaps = $snapchat->getSnaps();
    
    foreach ($snaps as $snap) {
      /*  
       echo "<br/>";
       var_dump($snap); 
        */
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
        $snapchat->markSnapViewed($snap->id);

	   //  upload as snap
        $id = $snapchat->upload(
           $snap->media_type, 
           file_get_contents($filename)
        );
         
        // delete the files
         unlink($filename);

         // add to story   
         $snapchat->setStory($id, $snap->media_type);
        
         // Screenshot to notify senders
	    $snapchat->markSnapShot($snap->id);	

        // for terminal testing
        echo "done";
    }

    // destroy evidence ;)
    $snapchat->clearFeed();
    echo "<br/>". $snapgroup["name"] . " refresh complete";
}
echo "<br/>all groups refreshed";