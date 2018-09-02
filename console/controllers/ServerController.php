<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.09.2018
 * Time: 4:27
 */
namespace console\controllers;

use api\daemons\SocketServer;
use yii\console\Controller;

class ServerController extends Controller
{
    /**
     * @var SocketServer $server
     */
    public $server;

    public function actionStart($port = null)
    {
        $this->server = new SocketServer();
        if ($port) {
            $this->server->port = $port;
        }
        $this->server->start();
    }

    public function actionStop()
    {
        $this->server->stop();
    }

}