<?php

define ('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/includes/lib/php-snapchat/src/snapchat.php');

set_time_limit (0);

// usernames and passwords
// should be replaced with db eventually

$snapgroups = array(
    array(
        "name" => "harvardyak",
        "pw" => "h@rv@rdy4k!"
    ),
    array(
        "name" => "BUYakk",
        "pw" => "h@rv@rdy4k!",
    ),
);
 
foreach ($snapgroups as $snapgroup) {
    $snapchat = new Snapchat($snapgroup["name"], $snapgroup["pw"]);

    $snapchat->updatePrivacy(Snapchat::PRIVACY_FRIENDS);
    
    $updates = $snapchat->getUpdates(true);
   
    if (!empty($updates)) {
        $added_friends = $updates->added_friends;
        
        $counter = 0; 
        foreach($added_friends as $added_friend) {
            $snapchat->addFriend($added_friend->name);
            $counter = $counter + 1; 
        } 
        
        echo "Added " . $counter . " friends \n";
    
        $snaps = array(); 
        foreach ($updates->snaps as $snap) {
            $snaps[] = (object) array(
                'id' => $snap->id,
                'media_id' => empty($snap->c_id) ? FALSE : $snap->c_id,
                'media_type' => $snap->m,
                'status' => $snap->st,
                'screenshot_count' => empty($snap->c) ? 0 : $snap->c,
                'sent' => $snap->sts,
                'opened' => $snap->ts,
           );
        }
        
        if ($snaps !== false) { 
            foreach ($snaps as $snap) {
              
                if ($snap->status == Snapchat::STATUS_OPENED 
                    || $snap->status == Snapchat::STATUS_SCREENSHOT) {
                    continue;
                } 
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
                
                 // mark snap as viewed
                $snapchat->markSnapViewed($snap->id);


                // for terminal testing
                echo "done";
            }
        }
    }

    // destroy evidence ;)
    $snapchat->clearFeed();
    echo "<br/>". $snapgroup["name"] . " refresh complete";
}
echo "<br/>all groups refreshed";
