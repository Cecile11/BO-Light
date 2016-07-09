<?php
namespace AppBundle\Tools;
use DateTime;
use DateInterval;
use DateTimeZone;

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
		$hour = (int) $dateTime->format('H');
		$ts = $dateTime->getTimestamp();
		$ts = $ts + (24 - $hour)*3600;
		$dateTime->setTimestamp($ts);
		return $dateTime;
	} 

	public function getDates($limit){
        $utc = new DateTimeZone('UTC');
		$dateAfter = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $this->roundDateDayUp($dateAfter);
        $dateBefore = new DateTime(null, new DateTimeZone('Europe/Paris'));
        $this->roundDateDayUp($dateBefore);
        $dateAfter->setTimezone($utc);
        $dateBefore->setTimezone($utc);
        switch ($limit) {
            case 'day-2':
                $oneInterval = new DateInterval('PT1H');
                $allInterval = new DateInterval('P1D');
                $dateAfter->sub(new DateInterval('P2D'));
                $dateBefore->sub(new DateInterval('P3D'));
                break;
            case 'day-3':
                $oneInterval = new DateInterval('PT1H');
                $allInterval = new DateInterval('P1D');
                $dateAfter->sub(new DateInterval('P3D'));
                $dateBefore->sub(new DateInterval('P4D'));
                break;
            case 'yesterday':
            	$oneInterval = new DateInterval('PT1H');
            	$allInterval = new DateInterval('P1D');
                $dateAfter->sub($allInterval);
                $dateBefore->sub(new DateInterval('P2D'));
                break;
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
            	$allInterval = new DateInterval('P4M');
                $dateBefore->sub($allInterval);
        }
        return array('dateAfter'=>$dateAfter,'dateBefore'=>$dateBefore,'oneInterval'=>$oneInterval,'allInterval'=>$allInterval);
	}
}