<?php 

exit('discard');

require __DIR__ . '/../vendor/autoload.php';


$write = new Pangou\Fifo\Fifo('/tmp/serv.fifo', '0666', false);
$read = new Pangou\Fifo\Fifo('/tmp/serv.fifo', '0666', true);
$write -> write('hello, fifo')
echo $read -> read(11);
