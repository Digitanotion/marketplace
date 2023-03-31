<?php 
namespace services\SecS;
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ ."\../../vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else{
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
class InputValidator
{
    public static $regexes = Array(
            'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
            'amount' => "^[-]?[0-9]+\$",
            'number' => "^[-]?[0-9,]+\$",
            'alfanum' => "^[0-9a-zA-Z ,.-_\\s\?\!]+\$",
            'not_empty' => "[a-z0-9A-Z]+",
            'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
            'phone' => "^[0-9]{10,11}\$",
            'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
            'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
            'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
            '2digitopt' => "^\d+(\,\d{2})?\$",
            '2digitforce' => "^\d+\,\d\d\$",
            'anything' => "^[\d\D]{1,}\$"
    );
    private $validations, $sanatations, $mandatories, $errors, $corrects, $fields;
    

    public function __construct($validations=array(), $mandatories = array(), $sanatations = array())
    {
        $this->validations = $validations;
        $this->sanitations = $sanatations;
        $this->mandatories = $mandatories;
        $this->errors = array();
        $this->corrects = array();
    }

    /**
     * Validates an array of items (if needed) and returns true or false
     *
     */
    public function validate($items)
    {
        $this->fields = $items;
        $havefailures = false;
        foreach($items as $key=>$val)
        {
            if((strlen($val) == 0 || array_search($key, $this->validations) === false) && array_search($key, $this->mandatories) === false) 
            {
                $this->corrects[] = $key;
                continue;
            }
            $result = self::validateItem($val, $this->validations[$key]);
            if($result === false) {
                $havefailures = true;
                $this->addError($key, $this->validations[$key]);
            }
            else
            {
                $this->corrects[] = $key;
            }
        }
    
        return(!$havefailures);
    }

    /**
     *
     *  Adds unvalidated class to thos elements that are not validated. Removes them from classes that are.
     */
    public function getScript() {
        if(!empty($this->errors))
        {
            $errors = array();
            foreach($this->errors as $key=>$val) { $errors[] = "'INPUT[name={$key}]'"; }

            $output = '$$('.implode(',', $errors).').addClass("unvalidated");'; 
            $output .= "new FormValidator().showMessage();";
        }
        if(!empty($this->corrects))
        {
            $corrects = array();
            foreach($this->corrects as $key) { $corrects[] = "'INPUT[name={$key}]'"; }
            $output .= '$$('.implode(',', $corrects).').removeClass("unvalidated");';   
        }
        $output = "<script type='text/javascript'>{$output} </script>";
        return($output);
    }


    /**
     *
     * Sanitizes an array of items according to the $this->sanitations
     * sanitations will be standard of type string, but can also be specified.
     * For ease of use, this syntax is accepted:
     * $sanitations = array('fieldname', 'otherfieldname'=>'float');
     */
    public function sanitize($items)
    {
        foreach($items as $key=>$val)
        {
            if(array_search($key, $this->sanitations) === false && !array_key_exists($key, $this->sanitations)) continue;
            $items[$key] = self::sanitizeItem($val, $this->validations[$key]);
        }
        return($items);
    }


    /**
     *
     * Adds an error to the errors array.
     */ 
    private function addError($field, $type='string')
    {
        $this->errors[$field] = $type;
    }

    /**
     *
     * Sanitize a single var according to $type.
     * Allows for static calling to allow simple sanitization
     */
    public function sanitizeItem($var, $type)
    {
        $flags = NULL;
        $output="";
        switch($type)
        {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
            break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
            break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
            break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
            break;
            case 'string':
                $filter = FILTER_SANITIZE_STRING;
                $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5;
                $output = $this->sanitizeInputs($var);  
            break;
            case 'stringLite':
                    $filter = FILTER_SANITIZE_STRING;
                    $flags = ENT_QUOTES | ENT_HTML401 ;
                    $output = $this->sanitizeInputs($var);
            break;
            default: 
             
        }
        $output = filter_var($var, $filter, $flags); 
                 
        return($output);
    }
    public static function sanitizeInput($var, $type)
    {
        $flags = NULL;
        $output="";
        switch($type)
        {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
            break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
            break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
            break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
            break;
            case 'string':
                $filter = FILTER_SANITIZE_STRING;
                $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5;
                $output = self::sanitizeInputs($var);  
            break;
            case 'stringLite':
                    $filter = FILTER_SANITIZE_STRING;
                    $flags = ENT_QUOTES | ENT_HTML401 ;
                    $output = self::sanitizeInputs($var);
            break;
            default: 
             
        }
        $output = filter_var($var, $filter, $flags); 
                 
        return($output);
    }
    
    public function sanitizeInputs($inputData){
        //This will sanitize every foriegn input
        $inputData=strip_tags($inputData);
        $inputData=htmlspecialchars($inputData);
        $inputData=stripslashes($inputData);
        //$inputData=escapeshellcmd($inputData);
        $inputData=htmlentities($inputData, ENT_QUOTES, 'UTF-8');
        return $inputData;
    }
    /** 
     *
     * Validates a single var according to $type.
     * Allows for static calling to allow simple validation.
     *
     */
    public static function validateItem($var, $type)
    {
        if(array_key_exists($type, self::$regexes))
        {
            $returnval =  filter_var($var, FILTER_VALIDATE_REGEXP, array("options"=> array("regexp"=>'!'.self::$regexes[$type].'!i'))) !== false;
            return($returnval);
        }
        $filter = false;
        switch($type)
        {
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;    
            break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
            break;
            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
            break;
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
            break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
            break;
        }
        return filter_var($var, $filter);
    }       
    


}

?>