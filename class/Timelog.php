<?php
//存讀使用者設定時間檔案
class Timelog
{

   var $filename;
   var $data;
   var $fileExist;

   function Timelog()
   {
       $this->filename = "/HDD/STATUSLOG/Timelog";
       if(is_file($this->filename)) {
           $this->data = file($this->filename);
           $this->fileExist = true;
       } else {
           $this->fileExist = false;
       }
   }

   function read_timelog()
   {
        $ret = array();
        if($this->fileExist) {
            foreach($this->data as $key => $val) {
                $ret[$key] = explode(",", $val);
            }
        }
        return $ret;
   }


    function find_timelog($sid)
    {
       if($this->fileExist) {
            foreach($this->data as $val) {
                $tmp = explode(",", $val);
                    if($tmp[0] == $sid) {
                        return $tmp[1];
                        break;
                    }
                
            }
       }
        return 0;
    }
    
    function write_timelog($str, $findSessionId=null)
    {
        
        if($this->fileExist) {
            if($findSessionId != null) {
                $found = false;
                foreach($this->data as $key => $val) {
                    $tmp = explode(",", $val);
                    if($tmp[0] == $findSessionId) {
                        $this->data[$key] = $str . "\n";
                        $found = true;
                        break;
                    }
                }
    
                if(!$found) {
                    $this->data[$key+1] = $str . "\n";
                }
                $tmpFile = $this->filename . "_tmp";
                $fp = fopen($tmpFile, "w+");
                foreach($this->data as $line) {
                    fwrite($fp, $line);
                }
                fclose($fp);
                exec("/bin/mv " . $tmpFile . " " . $this->filename);
                    
            } else {
                exec("/bin/echo \"" . $str ."\" >> " . $this->filename);
            }
        } else {
            exec("/bin/echo \"" . $str ."\" >> " . $this->filename);
        }    
    }
}