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
    protected $fp;

    /**
     * [__construct description]
     * @param [type] $name Path to the FIFO file.
     * @param [type] $mode The second parameter mode has to be given in octal notation (e.g. 0644). 
     */
    public function __construct(Protocol\iProtocol $protocol, $name, $mode, $isRead) {
        $this -> name     = $name;
        $this -> mode     = $mode;
        $this -> isRead   = $isRead;
        $this -> protocol = $protocol;


        $this -> createFifo();


        if ($this -> isRead) {
            $this -> fp = fopen($this -> name, 'r+');
        } else {
            $this -> fp = fopen($this -> name, 'w+');
        }

        if (!is_resource($this -> fp)) {
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
        return $this -> protocol -> read($this -> fp);
    }

    /**
     * write message
     * @return [type] [description]
     */
    public function write($message) {
        if ($this -> isRead) {
            return false;
        }
        return $this -> protocol -> wirte($this -> fp, $message);
    }

    /**
     * close fifo
     * @return [type] [description]
     */
    public function close() {
        if (is_resource($this -> fp)) {
            fclose($this -> fp);
        }
    }

    /**
     * unlike fifo file
     * @return [type] [description]
     */
    public function remove() {
        return unlink($this -> name);
    }

    public function __destruct() {
        $this -> close();
    }
}
