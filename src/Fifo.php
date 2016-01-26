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
    public function __construct($name, $mode, $isRead) {
        if (true !== posix_mkfifo($name, $mode)) {
            throw new \Exception('create fifo fail');
        }

        if ($isRead) {
            $this -> fp = fopen($this->filename, 'r+');
        } else {
            $this -> fp = fopen($this->filename, 'w+');
        }

        if (!is_resource($this -> fp)) {
            throw new \Exception("open fifo error");
        }


        $this -> name   = $name;
        $this -> mode   = $mode;
        $this -> isRead = $isRead;
    }

    /**
     * read msg
     * @param  integer $len [description]
     * @return [type]       [description]
     */
    public function read($len = 1024) {
        $if (!$isRead) {
            return false;
        }
        return fread($this -> fd, $size);
    }

    /**
     * write msg
     * @return [type] [description]
     */
    public function wirte($msg) {
        if ($isRead) {
            return false;
        }
        return fwrite($this -> fp, $msg);
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
