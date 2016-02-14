<?php 

namespace Pangou\Fifo;

class Fifo 
{
    /**
     * fifo name
     * @var [type]
     */
    protected $name;

    /**
     * fifo mode
     * @var [type]
     */
    protected $mode;

    /**
     * 是否读，否则写
     * @var [type]
     */
    protected $isRead;

    /**
     * fifo 文件描述符
     * @var [type]
     */
    protected $fd;

    /**
     * 是否阻塞
     * @var [type]
     */
    protected $isBlock;

    /**
     * [__construct description]
     * @param [type] $name Path to the FIFO file.
     * @param [type] $mode The second parameter mode has to be given in octal notation (e.g. 0644). 
     */
    public function __construct(Protocol\iProtocol $protocol, $name, $mode, $isRead, $isBlock = 1) {
        $this -> name     = $name;
        $this -> mode     = $mode;
        $this -> isRead   = $isRead;
        $this -> isBlock  = $isBlock;
        $this -> protocol = $protocol;

        $this -> createFifo();

        if ($this -> isRead) {
            $this -> fd = fopen($this -> name, 'r+');
        } else {
            $this -> fd = fopen($this -> name, 'w+');
        }

        if (!is_resource($this -> fd)) {
            throw new \Exception("open fifo error");
        }

    }

    /**
     * 创建fifo
     * @return [type] [description]
     */
    protected function createFifo() {
        if (file_exists($this -> name) && filetype($this -> name) != 'fifo') {
            copy($this -> name, $this -> name . '.backup');
            unlink($this -> name);
        }

        if (!file_exists($this -> name) && true !== posix_mkfifo($this -> name, $this -> mode)) {
            throw new \Exception('create fifo fail');
        }

        if (filetype($this -> name) != 'fifo') {
            throw new \Exception('fifo type error');
        }
    }

    /**
     * read message
     * @param  integer $len [description]
     * @return [type]       [description]
     */
    public function read() {
        if (!$this -> isRead) {
            return false;
        }
        return $this -> protocol -> read($this -> fd);
    }

    /**
     * write message
     * @return [type] [description]
     */
    public function write($message) {
        if ($this -> isRead) {
            return false;
        }
        return $this -> protocol -> wirte($this -> fd, $message);
    }

    /**
     * 设置是否阻塞
     * @param [type] $isBlock [description]
     */
    public function setBlock($isBlock) {
        if ($isBlock == $this -> isBlock) {
            return true;
        }

        if (stream_set_blocking($this -> fd, $isBlock)) {
            $this -> isBlock = $isBlock;
            return true;
        }
        return false;
    }

    /**
     * close fifo
     * @return [type] [description]
     */
    public function close() {
        if (is_resource($this -> fd)) {
            fclose($this -> fd);
        }
    }

    /**
     * unlike fifo file
     * @return [type] [description]
     */
    public function remove() {
        return unlink($this -> name);
    }

    /**
     * return fd
     * @return [type] [description]
     */
    public function getFd() {
        return $this -> fd;
    }

    public function __destruct() {
        $this -> close();
    }
}
