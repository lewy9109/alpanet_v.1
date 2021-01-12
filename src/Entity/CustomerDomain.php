<?php

namespace App\Entity;

use App\Repository\CustomerDomainRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CustomerDomainRepository::class)
 */
class CustomerDomain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customerDomains")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameDomain;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNameDomain(): ?string
    {
        return $this->nameDomain;
    }

    public function setNameDomain(string $nameDomain): self
    {
        $this->nameDomain = $nameDomain;

        return $this;
    }
}
