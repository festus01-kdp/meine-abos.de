<?php

declare(strict_types=1);
namespace App\Model;

use DateTime;
use JsonSerializable;

class Subscription implements JsonSerializable
{
    protected string $name;
    protected string $payments;
    protected DateTime $startDate;
    protected DateTime $cancelDate;
    protected array $paymentPeriod = ['Quarter','Monthly','Weekly'];
    protected String $paymentType;
    protected float $paymentAmount;

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'payments' => $this->payments,
            'startDate' => $this->startDate,
            'cancelDate' => $this->cancelDate,
            'paymentPeriod' => $this->paymentPeriod,
        ];
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

    public function getPaymenPeriod():array {
		return $this->paymentPeriod;
	}

	public function setPaymentPeriod(array $paymentPeriod):self {
		$this->paymentPeriod = $paymentPeriod;
        return $this;
	}  

	public function getStartDate():DateTime {
		return $this->startDate;
	}

	public function setStartDate(DateTime $startDate):self {
		$this->startDate = $startDate;
        return $this;
	}


    

}
