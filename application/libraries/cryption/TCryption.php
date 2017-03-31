<?php
/**
 * Created by PhpStorm.
 * User: mintgreen
 * Date: 2016/10/17
 * Time: 11:32
 */
class TCryption{
    private $maskArray;

    public function __construct($key='||$$||'){
        $this->maskArray=$this->stringToArray($key);
    }

    /**
     * 字符串转换成数组
     * @param $str
     * @return array
     */
    private function stringToArray($str){
        $str=''.$str;
        $array_return=str_split($str);
        return $array_return;
    }

    /**
     * 加密：字符ASCII+key的ASCII
     * @param $str
     * @return string|void
     */
    public function enCryption($str){
        if(empty($str)){
            return;
        }
        $array=$this->stringToArray($str);
        $endIndex=count($this->maskArray)-1;
        $index=0;
        $crpStr='';
        foreach($array as $value){
            $crpStr.=ord($value);//获取字母的ASCII值
            if($index>$endIndex){
                $index=0;
            }
            $crpStr.=ord($this->maskArray[$index]);
            $index++;
        }
        return $crpStr;
    }

    /**
     * 解密
     * @param $str
     * @return bool|string|void
     */
    public function deCryption($str){
        if(empty($str)){
            return;
        }
        $starIndex=0;
        $keyIndex=0;
        $cryStr='';
        while($starIndex<strlen($str)-1){
            $splitCode=''.ord($this->maskArray[$keyIndex]);
            $endIndex=strpos($str,$splitCode,$starIndex);//字符串在另一个字符串第一次出现的位置
            if($endIndex==false){
                return false;
            }
            $cryStr.=chr(substr($str,$starIndex,$endIndex-$starIndex));
            $starIndex+=strlen($splitCode);
            if($keyIndex>=count($this->maskArray)-1){
                $keyIndex=0;
            }else{
                $keyIndex++;
            }
        }
        return $cryStr;
    }
}
?>