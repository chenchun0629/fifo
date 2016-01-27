<?php 

require __DIR__ . '/../vendor/autoload.php';

$pid = pcntl_fork();

if ($pid == -1) {
    exit('fork error');
} else if ($pid == 0) {
    # child
    $read = new Pangou\Fifo\Fifo('/tmp/serv.fifo', '0666', true);
    echo $read -> read(11), "\n";
    echo "child over\n";
} else if ($pid > 0) {
    # parent
    $write = new Pangou\Fifo\Fifo('/tmp/serv.fifo', '0666', false);
    $write -> write('hello, fifo');
    pcntl_wait($status);
    echo "parent over\n";
}


