<?php
namespace CoreBundle\Tools;
use DateTime;
use DateInterval;
use DateTimeZone;
use stdClass;

class Tool{

	public function roundDateTimeUp(DateTime $dateTime){
		$minute = (int) $dateTime->format('i');
		$seconde = (int) $dateTime->format('s');
		$ts = $dateTime->getTimestamp();
		$ts = $ts - $seconde + 3600 - $minute * 60;
		$dateTime->setTimestamp($ts);
		return $dateTime;
	}

	public function roundDateDayUp(DateTime $dateTime){
		$dateTime = $this->roundDateTimeUp($dateTime);
		$hour = ((int) $dateTime->format('H') + $this->getDecalage('UTC','Europe/Paris'))%24;
		$ts = $dateTime->getTimestamp();
		$ts = $ts + (24 - $hour)*3600;
		$dateTime->setTimestamp($ts);
		return $dateTime;
	}

    public function getDecalage($remote_tz,$origin_tz){
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset/3600;
    }

	public function getDates($limit,$offset){
        $utc = new DateTimeZone('UTC');
		$dateAfter = new DateTime();
        $this->roundDateDayUp($dateAfter);
        $dateBefore = new DateTime();
        $this->roundDateDayUp($dateBefore);
        $dateAfter->setTimezone($utc);
        $dateBefore->setTimezone($utc);
        switch ($limit) {
            case 'today':
            	$oneInterval = new DateInterval('PT1H');
            	$allInterval = new DateInterval('P1D');
                $dateBefore->sub($allInterval);
                break;
            case 'week':
           		$oneInterval = new DateInterval('PT1H');
            	$allInterval = new DateInterval('P1D');
                $dateBefore->sub(new DateInterval('P8D'));
                break;
            case 'month':
            	$oneInterval = new DateInterval('P1D');
            	$allInterval = new DateInterval('P1M');
                $dateBefore->sub($allInterval);
                break;
            case 'quarter':
            	$oneInterval = new DateInterval('P1D');
            	$allInterval = new DateInterval('P1M');
                $dateBefore->sub($allInterval);
        }
        if ($offset > 0){
            for ($i=0; $i < $offset; $i++) { 
                $dateBefore->sub($allInterval);
                $dateAfter->sub($allInterval);
            }
        }
        switch ($limit) {
            case 'today':
                $date = $dateAfter->format('d/m');
                break;
            case 'week':
                $date = $dateAfter->format('d/m');
                break;
            case 'month':
                $date = $dateAfter->format('F Y');
                break;
            case 'quarter':
                $date="";
        }
        return array('dateAfter'=>$dateAfter,'dateBefore'=>$dateBefore,'oneInterval'=>$oneInterval,'allInterval'=>$allInterval,'date'=>$date);
	}

    public function aplanObject($data,&$object,$superKey=""){
        if (is_object($data)){
            foreach ($data as $key => $value) {
                $separator = ($superKey == "" ? "" : "_");
                $this->aplanObject($value,$object,$superKey.$separator.$key);
            }
        } else {
            $object->$superKey = $data;
        }
    }

    public function aplanArray($data){
        $result = array();
        foreach ($data as $value) {
            $object = new stdClass();
            $this->aplanObject($value,$object);
            $result[] = $object;
        }
        return $result;
    }
}