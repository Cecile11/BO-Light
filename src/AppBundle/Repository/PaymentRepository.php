<?php

namespace AppBundle\Repository;
use DateInterval;
use DateTime;
/**
 * PaymentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaymentRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAllByDate(DateTime $dateBefore,DateTime $dateAfter){
		$qb = $this->createQueryBuilder('payment');
		return $qb->where('payment.vadsEffectiveCreationDate < ?1')->andWhere('payment.vadsEffectiveCreationDate > ?2')->andWhere("payment.vadsPaymentType != 'RETRY'")->setParameters(array(1=>$dateAfter,2=>$dateBefore))->getQuery()->getResult();
	}

	public function findAllClientByDate($dates){
		$qb = $this->createQueryBuilder('payment');
		return $qb->select('DISTINCT payment.vadsCustId, payment.vadsCustFirstName, payment.vadsCustLastName')->where('payment.vadsEffectiveCreationDate > ?1')->andWhere('payment.vadsEffectiveCreationDate < ?2')->andWhere("payment.vadsPaymentType != 'RETRY'")->setParameters(array(1=>$dates['dateBefore'],2=>$dates['dateAfter']))->orderBy('payment.vadsEffectiveCreationDate','DESC')->getQuery()->getResult();
	}
	
	public function findNbCommandClient($client,$dates){
		$qb = $this->createQueryBuilder('payment');
		return $qb->select('COUNT(DISTINCT payment.uuid)')->where('payment.vadsEffectiveCreationDate > ?1 ')->andWhere("payment.vadsCustId = ?2")->setParameter(2,$client)->andWhere('payment.vadsEffectiveCreationDate < ?3 ')->andWhere("payment.vadsPaymentType != 'RETRY'")->setParameters(array(1=>$dates['dateBefore'],2=>$client,3=>$dates['dateAfter']))->getQuery()->getSingleScalarResult();
	}

	public function findNbRefusedClient($client,$dates){
		$qb = $this->createQueryBuilder('payment');
		return $qb->select('COUNT(DISTINCT payment.uuid)')->where('payment.vadsEffectiveCreationDate > ?1 ')->andWhere("payment.vadsCustId = ?2")->setParameter(2,$client)->andWhere('payment.vadsEffectiveCreationDate < ?3 ')->andWhere("payment.vadsPaymentType != 'RETRY'")->setParameters(array(1=>$dates['dateBefore'],2=>$client,3=>$dates['dateAfter']))->andWhere("payment.vadsTransStatus = 'REFUSED'")->getQuery()->getSingleScalarResult();
	}

	public function findTtAmountClient($client,$dates){
		$qb = $this->createQueryBuilder('payment');
		return $qb->select('SUM(payment.vadsEffectiveAmount)')->where('payment.vadsEffectiveCreationDate > ?1 ')->andWhere("payment.vadsCustId = ?2")->setParameter(2,$client)->andWhere('payment.vadsEffectiveCreationDate < ?3 ')->andWhere("payment.vadsPaymentType != 'RETRY'")->andWhere("payment.vadsTransStatus != 'REFUSED'")->setParameters(array(1=>$dates['dateBefore'],2=>$client,3=>$dates['dateAfter']))->getQuery()->getSingleScalarResult() + 0;
	}

	public function findNbCommand(DateTime $dateBefore,DateTime $dateAfter){
		$qb = $this->createQueryBuilder('payment');
		return $qb->where('payment.vadsEffectiveCreationDate > ?1 ')->setParameter(1,$dateBefore)->andWhere('payment.vadsEffectiveCreationDate <= ?2 ')->andWhere("payment.vadsPaymentType != 'RETRY'")->setParameter(2,$dateAfter)->select('COUNT(payment)')->getQuery()->getSingleScalarResult();
	}

	public function findNbClient(DateTime $dateBefore, DateTime $dateAfter){
		$qb = $this->createQueryBuilder('payment');
		return $qb->where('payment.vadsEffectiveCreationDate > ?1 ')->setParameter(1,$dateBefore)->andWhere('payment.vadsEffectiveCreationDate <= ?2 ')->andWhere("payment.vadsPaymentType != 'RETRY'")->setParameter(2,$dateAfter)->select('COUNT(DISTINCT payment.vadsCustId)')->getQuery()->getSingleScalarResult();
	}

	public function findNbAccepted(DateTime $dateBefore, DateTime $dateAfter){
		$qb = $this->createQueryBuilder('payment');
		$qb->where('payment.vadsEffectiveCreationDate > ?1 ')->setParameter(1,$dateBefore)->andWhere('payment.vadsEffectiveCreationDate <= ?2 ')->setParameter(2,$dateAfter);
		return $qb->select('COUNT(payment.uuid)')->andWhere("payment.vadsPaymentType != 'RETRY'")->andWhere("payment.vadsTransStatus != 'REFUSED'")->getQuery()->getSingleScalarResult() + 0;
	}

	public function findNbRefused(DateTime $dateBefore, DateTime $dateAfter){
		$qb = $this->createQueryBuilder('payment');
		return $qb->where('payment.vadsEffectiveCreationDate > ?1 ')->setParameter(1,$dateBefore)->andWhere('payment.vadsEffectiveCreationDate <= ?2 ')->setParameter(2,$dateAfter)->select('COUNT(DISTINCT payment.uuid)')->andWhere("payment.vadsPaymentType != 'RETRY'")->andWhere("payment.vadsTransStatus = 'REFUSED'")->getQuery()->getSingleScalarResult();
	}

	public function findTtAmount(DateTime $dateBefore,DateTime $dateAfter){
		$qb = $this->createQueryBuilder('payment');
		return $qb->select('SUM(payment.vadsEffectiveAmount)')->where('payment.vadsEffectiveCreationDate > ?1 ')->setParameter(1,$dateBefore)->andWhere('payment.vadsEffectiveCreationDate <= ?2 ')->setParameter(2,$dateAfter)->andWhere("payment.vadsTransStatus != 'REFUSED'")->andWhere("payment.vadsPaymentType != 'RETRY'")->getQuery()->getSingleScalarResult() + 0;
	}

}
