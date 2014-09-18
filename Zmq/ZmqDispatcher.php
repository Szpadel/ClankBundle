<?php
/**
 * Created by PhpStorm.
 * User: peecdesktop
 * Date: 18.09.14
 * Time: 19:21
 */

namespace JDare\ClankBundle\Zmq;


use Symfony\Component\DependencyInjection\ContainerAware;

class ZmqDispatcher extends ContainerAware{


    public function send (ZmqMessage $message) {
        $found = false;
        $zmq = $this->container->getParameter('jdare_clank.zmq_configuration');

        if (!$zmq['enabled']) {
            throw new \Exception("ZMQ is not enabled. Add zmq:\nenabled: true\n to the configuration for clank. in config.yml");
        }

        foreach($this->container->get("jdare_clank.clank_handler_topic")->getTopicServices() as $service) {
            if ($message->getName() == $service['name']) {
                $found = true;
            }
        }

        if (false === $found) {
            throw new \Exception("Could not find topic service with name {$message->getName()}. Name should be equal to the topic: - name in clank configuration (config.yml).");
        }

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:{$zmq['port']}");

        $socket->send(json_encode(array(
            'name' => $message->getName(),
            'data' => $message->getData()
        )));
    }

} 