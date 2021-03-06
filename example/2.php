<?php 

require __DIR__ . '/../vendor/autoload.php';

$pid = pcntl_fork();

if ($pid == -1) {
    exit('fork error');
} else if ($pid == 0) {
    # child
    
    $protocol = new Pangou\Fifo\Protocol\LengthProtocol();
    $read = new Pangou\Fifo\Fifo($protocol, '/tmp/serv.fifo', '0666', true);
    echo $read -> read(), "\n";
    echo "child over\n";
} else if ($pid > 0) {
    # parent
    $protocol = new Pangou\Fifo\Protocol\LengthProtocol();
    $write = new Pangou\Fifo\Fifo($protocol, '/tmp/serv.fifo', '0666', false);
    $write -> write('hello, fifo');
    pcntl_wait($status);
    echo "parent over\n";
}


