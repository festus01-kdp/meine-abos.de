<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use JsonSerializable;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 */
class Subscription
{

    protected RouterInterface $Router;

    public function __construct(RouterInterface $router)
    {

        $this->Router = $router;
    }

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

    /**
     * @ORM\ManyToOne(targetEntity=PaymentType::class, inversedBy="subscriptions")
     */
    protected ?PaymentType $paymentType;

    // protected array $paymentPeriod = ['Quarter','Monthly','Weekly'];
    // protected String $paymentType;
    // protected float $paymentAmount;


    public function getId(): int
    {
        return $this->Id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPayments(): string
    {
        return $this->payments;
    }

    public function setPayments(string $payments): self
    {
        $this->payments = $payments;
        return $this;
    }

    public function getCancelDate(): DateTime
    {
        return $this->cancelDate;
    }

    public function setCancelDate(DateTime $cancelDate): self
    {
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

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

}
