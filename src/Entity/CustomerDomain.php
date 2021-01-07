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
     * @ORM\Column(type="string", length=255)
     */
    private $name_domain;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customerDomains")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameDomain(): ?string
    {
        return $this->name_domain;
    }

    public function setNameDomain(string $name_domain): self
    {
        $this->name_domain = $name_domain;

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
}
