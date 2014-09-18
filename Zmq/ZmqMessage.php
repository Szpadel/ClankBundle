<?php
/**
 * Created by PhpStorm.
 * User: peecdesktop
 * Date: 18.09.14
 * Time: 19:31
 */

namespace JDare\ClankBundle\Zmq;


class ZmqMessage {

    protected $name;

    protected $data;

    public function __construct ($name, $data) {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return mixed Custom data.
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return string Name of the service for the Topic.
     */
    public function getName() {
        return $this->name;
    }



}