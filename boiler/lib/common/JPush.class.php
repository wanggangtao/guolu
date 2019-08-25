<?php
require_once 'jpush/autoload.php';

use JPush\Client as JPush;

use JPush\Exceptions\APIConnectionException;
use JPush\Exceptions\APIRequestException;

function JPUSH_send($register_id, $message, $title, $projectid, $msgid){

    $app_key='00cab62f652a2ae8dd5f2853';
    $master_secret = '8c1c9f32773bae7b84b29d46';

    $client = new JPush($app_key, $master_secret);

    try {
        $result = $client->push()
            ->setPlatform(array('ios', 'android'))
            ->addRegistrationId($register_id)
            ->setNotificationAlert($title)
            ->iosNotification($message, array(
                'sound' => 'sound.caf',
                'badge' => '+1',
                'content-available' => true,
                'mutable-content' => true,
                'category' => $title,
                'extras' => array(
                    "projectid" => $projectid,
                    "msgid" => $msgid,
                    'tag' => 2,
                    'title' => $title,
                ),
            ))
            ->androidNotification($message, array(
                'title' => $title,
                'extras' => array(
                    "projectid" => $projectid,
                    "msgid" => $msgid,
                    'tag' => 2,
                    'title' => $title,
                ),
            ))
            ->message($message, array(
                'title' => $title,
                'extras' => array(
                    'projectid' => $projectid,
                    "msgid" => $msgid,
                ),
            ))
            ->options(array(
                'apns_production' => true,
            ))
            ->send();

        return 1;
    } catch (APIRequestException $e) {
        return 0;
    } catch (APIConnectionException $e) {
        return 0;
    }
}

