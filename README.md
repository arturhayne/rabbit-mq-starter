## rabbit-mq-starter
Hello world using rabbitMQ following https://www.rabbitmq.com/tutorials/tutorial-one-php.html


## Run rabbitmq
<code>$ docker run -d --hostname my-rabbit --name some-rabbit -p 8080:15672 -p 5672:5672 rabbitmq:management-alpine</code>

   - To access the server:
http://localhost:8080/#/queues
(user: guest/ pass: guest)


## Install Dependecies
composer.phar install

## Run
To send the message:
   - php -f send.php

To receive the message:
   - php -f receive.php

