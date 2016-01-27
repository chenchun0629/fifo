<?php 

namespace Pangou\Fifo\Protocol;


class LengthProtocol implements iProtocol
{
    protected $maxLength = 0;
    protected $big = 0;

    /**
     * 初始化最长位数
     * @param  integer $bit [description]
     * @return [type]       [description]
     */
    public function __construct($bit = 9) {
        if ($bit <= 0 || $bit > 9) {
            $bit = 9;
        }
        $this -> bit = $bit;
        $this -> maxLength = pow(10, $bit);
    }

    /**
     * 写
     * @param  [type] $fp      [description]
     * @param  [type] $message [description]
     * @return [type]          [description]
     */
    public function wirte($fp, $message) {
        $len = strlen($message);
        if ($len > $this -> maxLength) {
            return false;
        }

        $len = str_pad($len, $this -> bit, '0', STR_PAD_LEFT);

        return fwrite($fp, $len.$message);
    }

    /**
     * 读
     * @param  [type] $fp [description]
     * @return [type]     [description]
     */
    public function read($fp) {
        $len = fread($fp, $this -> bit);
        if (empty($len)) {
            return false;
        }

        $str = '';
        while (strlen($str) < $len) {
            $readLength = ($len - strlen($str)) > 1024 ? 1024 : ($len - strlen($str));
            $str .= fread($fp, $readLength);
        }

        return $str;
    }
}
