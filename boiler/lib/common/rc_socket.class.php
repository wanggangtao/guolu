<?php
/**
 * Created by PhpStorm.
 * User: zhima
 * Date: 2019/3/5
 * Time: 下午3:50
 */

class rc_socket{
    private $socket;
    private $host='';
    private $port='';

    public function __construct($host,$port){
        $this->host=$host;
        $this->port=$port;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if(!$this->socket){
            echo "socket_create() failed: " . socket_strerror(socket_last_error());
            exit('Create socket failed!');
        }
        // set no data read timeout
        socket_set_option($this->socket, SOL_SOCKET, SO_KEEPALIVE, array("sec"=>0,"usec"=>100000));
        $result = socket_connect($this->socket,$this->host,$this->port);


        if(!$result){
            echo "socket_connect() failed: " . socket_strerror(socket_last_error($this->socket));
            exit('Connect to socket failed!');
        }
        socket_set_nonblock($this->socket);
    }


    public function __desctruct(){
        socket_close($this->socket);
    }

    public function send($data){
        $result=socket_write($this->socket,$data);
        if(!$result){
            exit('Sent data to socket failed!');
        }
        else
        {
            print_r($result);
        }
    }

    public function recv($cnt){
        return socket_read($this->socket, $cnt);
    }
}
