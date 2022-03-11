<?php

declare(strict_types=1);
namespace App\Entity;

use DateTime;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository") 
 */


class Subscription implements JsonSerializable
{

	/** 
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer") 
	 */
	protected int $Id; 

	/** 
	 * @ORM\Column(type="string", length=255) 
	 * @Assert\NotBlank
	*/
    protected string $name;
    
	/** 
	 * @ORM\Column(type="string", length=255) 
	 * @Assert\NotBlank
	*/
	protected string $payments;

	/**
	 * @ORM\Column(type="datetime")
	 * 
	 */
    protected DateTime $startDate;

	/**
	 * @ORM\Column(type="datetime")
	 * 
	 */
    protected DateTime $cancelDate;

	// protected array $paymentPeriod = ['Quarter','Monthly','Weekly'];
    // protected String $paymentType;
    // protected float $paymentAmount;

    public function jsonSerialize(): mixed
    {
        return [
			'id' => $this->Id,
            'name' => $this->name,
            'payments' => $this->payments,
            'startDate' => $this->startDate,
            'cancelDate' => $this->cancelDate,
            // 'paymentPeriod' => $this->paymentPeriod,
        ];
    }

	public function getId():int
	{
		return $this->Id;
	}

	public function getName():string {
		return $this->name;
	}

	public function setName(string $name):self {
		$this->name = $name;
        return $this;
	}

    public function getPayments():string {
		return $this->payments;
	}

	public function setPayments(string $payments):self {
		$this->payments = $payments;
        return $this;
	}    

    public function getCancelDate():DateTime {
		return $this->cancelDate;
	}

	public function setCancelDate(DateTime $cancelDate):self {
		$this->cancelDate = $cancelDate;
        return $this;
	}  

    // public function getPaymenPeriod():array {
	// 	return $this->paymentPeriod;
	// }

	// public function setPaymentPeriod(array $paymentPeriod):self {
	// 	$this->paymentPeriod = $paymentPeriod;
    //     return $this;
	// }  

	public function getStartDate():DateTime {
		return $this->startDate;
	}

	public function setStartDate(DateTime $startDate):self {
		$this->startDate = $startDate;
        return $this;
	}


    

}
