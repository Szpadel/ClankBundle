# Integration with ZMQ

Sometimes you would want to publish events from a controller to the Websocket server, forexample a notification system. See more of how it's integrated [here](http://socketo.me/docs/push).


## Server setup

First, you should Install ZMQ, and the PHP bindings.

Download ZMQ:

- http://zeromq.org/area:download

And the php bindings:

- http://zeromq.org/bindings:php




## Configuration

Add ZMQ as enabled:

```
clank:
    topic:
        # Example topic service:
        -
            name: "notification"
            service: "acme.topic.notification"
    # Enable ZMQ
    zmq:
        enabled: true
        port: 5555 # This should not be the same port as the Clank server.
```

## Sending messages from the controller

```
$this->container->get('jdare_clank.zmq.dispatcher')->send(new \JDare\ClankBundle\Zmq\ZmqMessage(
    "notification", // Reference the topic service associated with this message.
    ["hello" => "World"] // Some data
));
```

## Subscribing to ZMQ Messages.

```
use JDare\ClankBundle\Topic\TopicInterface;
use JDare\ClankBundle\Zmq\ZMQMessageReciever;
use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\Topic;


class NotificationTopic implements TopicInterface, ZMQMessageReciever
{

    /**
     * When a ZMQ message is recieved for this Topic, this handler is called.
     * @param Topic $topic The Topic instance
     * @param $data
     * @return mixed
     */
    public function onZMQMessage(Topic $topic, $data) {
        print_r($data); // Prints the data recieved in the console.  Only if someone has subscribed to this channel.
        $topic->broadcast($data);
    }

}
```

## Example simple client

Note you must include the JS files, they are in the bundles Resources folder.

```
<!doctype html>
<html>
<head>
<script src="Clank.js"></script>
<script src="autobahn.min.js"></script>
</head>
<body>
<script>
var myClank = Clank.connect("ws://10.0.0.4:8088");

myClank.on("socket/connect", function(session){
    //session is an Autobahn JS WAMP session.

    console.log("Successfully Connected!");

	//the callback function in "subscribe" is called everytime an event is published in that channel.
    session.subscribe("notification/channel", function(uri, payload){
        console.log("Received message", payload);
    });
})

myClank.on("socket/disconnect", function(error){
    //error provides us with some insight into the disconnection: error.reason and error.code

    console.log("Disconnected for " + error.reason + " with code " + error.code);
})


</script>
</body>
</html>
```