<?php

declare(strict_types=1);
namespace App\Entity;

use DateTime;
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

}
