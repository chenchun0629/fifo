<?php 

namespace Pangou\Fifo\Protocol;

interface iProtocol {
    public function wirte($fp, $message);
    public function read($fp);
}
