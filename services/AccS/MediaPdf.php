<?php
namespace services\AccS;

    class Uploader
    {
        private $destinationPath;
        private $errorMessage = [];
        private $extensions;
        private $allowAll;
        private $maxSize;
        private $uploadName;
        private $sameName;
        private $imageSeq;
        private $seqnence;
        public $name='Uploader';
        public $useTable    =false;

        function setDir($path){
            $this->destinationPath  =   $path;
            $this->allowAll =   false;
        }

        function allowAllFormats(){
            $this->allowAll =   true;
        }

        function setMaxSize($sizeMB){
            $this->maxSize  =   $sizeMB * (1024*1024);
        }

        function setExtensions($options){
            $this->extensions   =   $options;
        }

        function setSameFileName(){
            $this->sameFileName =   true;
            $this->sameName =   true;
        }
        function getExtension($string){
            $ext    =   "";
            try{
                    $parts  =   explode(".",$string);
                    $ext        =   strtolower($parts[count($parts)-1]);
            }catch(Exception $c){
                    $ext    =   "";
            }
            return $ext;
    }

        // function message($message){
        //     $this->errorMessage =   $message;
        // }

        function getMessage(){
            return $this->errorMessage;
        }

        function getUploadName(){
            return $this->uploadName;
        }
        function setSequence($seq){
            $this->imageSeq =   $seq;
    }

    function getRandom(){
        return strtotime(date('Y-m-d H:i:s')).rand(1111,9999).rand(11,99).rand(111,999);
    }
    function sameName($true){
        $this->sameName =   $true;
    }
        function uploadFile($fileBrowse, $fileName){
            //elexis

            $result =   false;
            $size   =   implode("", $fileBrowse["size"]);
            $name   =   implode(".", $fileBrowse["name"]);
            $ext    =   $this->getExtension($name);
            if(!is_dir($this->destinationPath)){
                $this->message(0, "Destination folder is not a directory ");
            }else if(!is_writable($this->destinationPath)){
                $this->message(0, "Destination is not writable !");
            }else if(empty($name)){
                $this->message(0, "File not selected ");
            }else if($size>$this->maxSize){
                $this->message(0, "Too large file !");
            }else if($this->allowAll || (!$this->allowAll && in_array($ext,$this->extensions))){

        if($this->sameName==false){
                    $this->uploadName   =  $this->imageSeq.$fileName.".".$ext;
                }else{
            $this->uploadName=  $name;
        }
                if(move_uploaded_file(implode('', $fileBrowse["tmp_name"]),$this->destinationPath.$this->uploadName)){
                    $result= $this->message(1, "Upload");
                }else{
                    $this->message(0, "Upload failed , try later !");
                }
            }else{
                $this->message(0, "Invalid file format!");
            }
            return $result;
        }

        function deleteUploaded(){
            unlink($this->destinationPath.$this->uploadName);
        }

        protected function message($key, $value){
            $this->errorMessage["status"] = $key;
            $this->errorMessage["message"] = $value;
        }

    //end of class

    }

    

    

?>