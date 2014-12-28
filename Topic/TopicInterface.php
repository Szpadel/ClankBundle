<?php

namespace JDare\ClankBundle\Topic;

use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\Topic;

interface TopicInterface
{
    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return mixed
     */
     public function onSubscribe(Conn $conn, $topic);

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @return mixed
     */
     public function onUnSubscribe(Conn $conn, $topic);

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @param $topic
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed
     */
     public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible);

     /**
     * @param \Ratchet\Wamp\Topic $topic
     * @return void
     */
    public function setTopic(Topic $topic);
}