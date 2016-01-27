<?php 

require __DIR__ . '/../vendor/autoload.php';

$protocol = new Pangou\Fifo\Protocol\LengthProtocol();

$write = new Pangou\Fifo\Fifo($protocol, '/tmp/serv.fifo', '0666', false);
$read = new Pangou\Fifo\Fifo($protocol, '/tmp/serv.fifo', '0666', true);
$write -> write('hello, fifo!!!');
echo $read -> read();
