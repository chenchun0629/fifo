<?php 

require __DIR__ . '/../vendor/autoload.php';

$protocol = new Pangou\Fifo\Protocol\LengthProtocol();

$write = new Pangou\Fifo\Fifo($protocol, '/tmp/serv.fifo', '0666', false);
$read = new Pangou\Fifo\Fifo($protocol, '/tmp/serv.fifo', '0666', true);

$a = microtime(true);
$str = str_pad('a', 16);
$len = 0;
for ($i = 0; $i <= 100000; $i++) {
    // echo $len += strlen($str), " i = ", $i, "\n";
    $write -> write($str);
    $read -> read();
}
$b = microtime(true);
echo $b - $a;

// echo $read -> read();
