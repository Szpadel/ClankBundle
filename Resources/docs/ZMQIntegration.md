# Integration with ZMQ

Sometimes you would want to publish events from a controller to the Websocket server, for example a notification system.
See more of how it's integrated [here](http://socketo.me/docs/push).


## Server setup

First, you should Install ZMQ, and the PHP bindings. This should be installed on your server.

Download ZMQ:

- http://zeromq.org/area:download

And the php bindings:

- http://zeromq.org/bindings:php




## Configuration

Add ZMQ as enabled:

```yaml
clank:
    topic:
        # Example topic service:
        -
            name: "notification"
            service: "acme.topic.notification"
    # Enable ZMQ
    zmq:
        enabled: true
        port: 5555 # This should not be the same port as the Clank(Ratchet) server. Default is 5555
```

When starting the server with `clank:server` you should now see somthing like this:

```bash
peec@dev:$ php app/console clank:server
Starting Clank
Launching Ratchet WS Server on: 0.0.0.0:8088

Listening to ZMQ messages on tcp://127.0.0.1:5555
```




## Sending messages from the controller

From any controller, we can now send messages like this:

```php
$this->container->get('jdare_clank.zmq.dispatcher')->send(new \JDare\ClankBundle\Zmq\ZmqMessage(
    "notification", // Reference the topic service associated with this message.
    ["hello" => "World"] // Some data
));
```

## Subscribing to ZMQ Messages.

```php
use JDare\ClankBundle\Topic\TopicInterface;
use JDare\ClankBundle\Zmq\ZMQMessageReciever;
use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\Topic;


class NotificationTopic implements TopicInterface, ZMQMessageReciever
{


    // .. other onX functions here..

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

- You must include the JS files, they are in the bundles Resources folder.
- Change `ws://127.0.0.1:8088` to your clank server definition.

```html
<!doctype html>
<html>
<head>
<script src="Clank.js"></script>
<script src="autobahn.min.js"></script>
</head>
<body>
<script>
var myClank = Clank.connect("ws://127.0.0.1:8088");

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


Now open a controller where you use `jdare_clank.zmq.dispatcher` and you should see the message in the client (console.log).