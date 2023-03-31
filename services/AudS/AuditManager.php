<?php 
//Auditing Services Class goes here
//Import dependencies
namespace services\AudS;
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
USE services\SecS\SecurityManager;
USE services\InitDB;
class AuditManager{
    
    function publishRecentActivity(String $userKey, bool $logApprove, $logMessage, Int $logImpact, Int $logType){
        //This behavior handles logging
        $secure= new SecurityManager();
        $logHash="Log-".$secure->generateProgramHash("New Log Hash Token");
        $logTime=time();
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
        $stmt=$dbHandler->run("INSERT INTO audit_table (user_key, log_approve,log_message,log_time,log_hash,log_impact,log_type) VALUES (?,?,?,?,?,?,?)", [$userKey,$logApprove,$logMessage,$logTime,$logHash,$logImpact,$logType]);
        if ($stmt->rowCount()==1){
            return true;
        }
        else return false;
    }
    function fetchActivities(){
        //This behavior handles logging
        
        try {
            $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
            $stmt=$dbHandler->run("SELECT * FROM audit_table ORDER BY user_id DESC");
            if ($stmt->rowCount()>0){
                return $stmt;
            }
            else{
                return false;
            } 
        } catch (\PDOException $e) {
            //throw $th;
        }
       
    }

    function time_ago($timestamp){
        //Handles Ago time, like, 1hour or 1 year ago, so it its an algorithm that converts supplied timestamp to time interval
        $etime = time() - $timestamp;

            if ($etime < 1)
            {
                return '0 seconds';
            }

            $a = array( 365 * 24 * 60 * 60  =>  'year',
                        30 * 24 * 60 * 60  =>  'month',
                            24 * 60 * 60  =>  'day',
                                60 * 60  =>  'hour',
                                        60  =>  'minute',
                                        1  =>  'second'
                        );
            $a_plural = array( 'year'   => 'years',
                            'month'  => 'months',
                            'day'    => 'days',
                            'hour'   => 'hours',
                            'minute' => 'minutes',
                            'second' => 'seconds'
                        );

            foreach ($a as $secs => $str)
            {
                $d = $etime / $secs;
                if ($d >= 1)
                {
                    $r = round($d);
                    return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) ;
                }
            }
    }
    //Handle Greeting
    function greet_now(){
        $Hour = date('G');
        $greet ="";
        if ( $Hour >= 5 && $Hour <= 11 ) {
            $greet = "Good Morning";
        } else if ( $Hour >= 12 && $Hour <= 18 ) {
            $greet = "Good Afternoon";
        } else if ( $Hour >= 19 || $Hour <= 4 ) {
            $greet = "Good Evening";
        }
        return $greet;
    }
}
?>