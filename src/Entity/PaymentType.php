<?php

declare(strict_types=1);
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity(repositoryClass="App\Repository\PaymentTypeRepository")
 */

class PaymentType implements JsonSerializable
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    protected ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="paymentType")
     */
    private $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function jsonSerialize(): array
    {
        return [
			'id' => $this->Id,
            'name' => $this->name,
            'description' => $this->description ?? '',
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

    public function getDescription():?string {
		return $this->description;
	}

	public function setDescription(?string $description):self {
                        		$this->description = $description;
                                return $this;
                        	}

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setPaymentType($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getPaymentType() === $this) {
                $subscription->setPaymentType(null);
            }
        }

        return $this;
    }

}
