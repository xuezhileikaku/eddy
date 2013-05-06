<?php
$iparr = include('../conf/ip.php');

$serv = file_get_contents('../conf/curserv.txt');
$ip = $iparr[$serv]['ip'];
$port = $iparr[$serv]['port'];

define('SERVER_ADDRESS', $ip);
define('SERVER_PORT', $port);

//file_put_contents('./log.txt',SERVER_ADDRESS);
#NOTES:
#leverages stored in mt4 databse as integers. For example leverage=200 means that leverage 1:200
#time - unix timestamp format
#volumes stored in mt4 database as integers. 
#MT4 typical volume format = volume/100. For example volume=70 means 0.7 in MT4 ; volume=120 means 1.2 in MT4

class CMT4DataReciver {

    var $socketPtr;
    var $secretHashValue = "none";
    var $encryptionKey = "none";

    function OpenConnection($server, $port) {
        $this->socketPtr = fsockopen($server, $port, $errno, $errstr, 15);
        if (!$this->socketPtr) {
            return -1;
        } else {
            return 0;
        }
    }

    function SetSafetyData($secretHashValue, $encryptionKey) {
        $this->secretHashValue = $secretHashValue;
        $this->encryptionKey = $encryptionKey;
    }

    function _parse_answer($answerData) {
        $result = array();
        $data = explode("&", $answerData);
        foreach ($data as $piece) {
            $keyval = explode("=", $piece, 2);
            $result[$keyval[0]] = $keyval[1];
        }
        //return $result;
        return $answerData;
    }

    function MakeRequest($action, $params = array()) {
        if (!$this->socketPtr)
            return "error";

        $request_id = 9966; //rand();
        $request = "action=$action&request_id=$request_id";

        foreach ($params as $key => $value) {
            $request .= "&$key=$value";
        }

        if ($this->secretHashValue != "none") {
            $hash = $this->makeHash($action, $request_id);
            $request .= "&hash=$hash";
        }

        $request .= "\0"; // leading zero. It must be added to the end of each request		
        if ($this->encryptionKey != "none") {
            $request = $this->cryptography($request);
        }

        if ($request == "")
            return "error";
        $this->sendRequest($request);

        return $this->_parse_answer($this->readAnswer());
    }

    function CloseConnection() {
        fclose($this->socketPtr);
    }

    function sendRequest($request) {
        fputs($this->socketPtr, $request);
    }

    function readAnswer() {
        $size = fgets($this->socketPtr, 64);


        //echo $size;	
        return $size;
        $answer = "";
        $readed = 0;

        while ($readed < $size) {
            $part = fread($this->socketPtr, $size - $readed);
            $readed += strlen($part);
            $answer .= $part;
        }
        if ($this->encryptionKey != "none") {
            //$answer = $this->cryptography($answer, $encryptionKey);
        }

        //return $answer;
    }

    function makeHash($action, $request_id) {
        return md5($request_id . $action . $this->secretHashValue);
    }

    function cryptography($data) {
        $keyLen = strlen($this->encryptionKey);
        $keyIndex = 0;
        for ($i = 0; $i < strlen($data); $i++) {
            $data[$i] = $data[$i] ^ $this->encryptionKey[$keyIndex];
            $keyIndex++;
            if ($keyIndex == $keyLen)
                $keyIndex = 0;
        }
        return $data;
    }

}
?>