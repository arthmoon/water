<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.09.2018
 * Time: 4:25
 */
namespace api\daemons;

use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\WebSocketServer;

class SocketServer extends WebSocketServer
{

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_MESSAGE, function (WSClientMessageEvent $e) {
            $e->client->send( $e->message . 123 );
        });
    }

}