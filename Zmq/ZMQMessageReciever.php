<?php
/**
 * Created by PhpStorm.
 * User: peecdesktop
 * Date: 18.09.14
 * Time: 19:56
 */

namespace JDare\ClankBundle\Zmq;


use Ratchet\Wamp\Topic;

interface ZMQMessageReciever {

    /**
     * When a ZMQ message is recieved for this Topic, this handler is called.
     * @param Topic $topic The Topic instance
     * @param $data
     * @return mixed
     */
    public function onZMQMessage (Topic $topic, $data);

} 