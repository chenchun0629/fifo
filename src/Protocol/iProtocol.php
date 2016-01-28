<?php 

namespace Pangou\Fifo\Protocol;

interface iProtocol {
    public function wirte($fd, $message);
    public function read($fd);
}
