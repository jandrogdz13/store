<?php

final class Log
{
    private static $instance = [];
    private $handle;

    private function __construct($filename = '')
    {
    	if($filename):
			$filename = ROOT . 'logs/'.$filename.'.log';
			$this->handle = fopen($filename, 'a');
		endif;
    }

    public function write($message, $title = 'Section', $encode = false)
    {
        $message = $encode ? json_encode($message) : $message;
        fwrite($this->handle, $title . '@ ' . date('Y-m-d G:i:s') . '  ' . print_r($message, true) . "\n");
        //fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    public static function getInstance($filename)
    {
        if (isset(self::$instance[$filename]) instanceof Log) {
            return self::$instance[$filename];
        }
        self::$instance[$filename] = new self($filename);
        return self::$instance[$filename];
    }
}
