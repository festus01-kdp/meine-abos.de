<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
        $this->payments = new ArrayCollection();
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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $costs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $period;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptions")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="subscription", orphanRemoval=true)
     */
    private $payments;

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

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function setPayments(Collection $payments): self
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

    public function getCosts(): ?float
    {
        return $this->costs;
    }

    public function setCosts(?float $costs): self
    {
        $this->costs = $costs;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(?int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setSubscription($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getSubscription() === $this) {
                $payment->setSubscription(null);
            }
        }

        return $this;
    }

}
