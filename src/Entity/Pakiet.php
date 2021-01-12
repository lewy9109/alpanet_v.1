<?php

namespace App\Entity;

use App\Repository\PakietRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PakietRepository::class)
 */
class Pakiet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="pakiet", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $pakiet_start;

    /**
     * @ORM\Column(type="date")
     */
    private $pakiet_end;

    /**
     * @ORM\Column(type="integer")
     */
    private $pakiet_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rest_min;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pakiet_time_byYear;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $settlement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status_pakiet;

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

    public function getPakietStart(): ?\DateTimeInterface
    {
        return $this->pakiet_start;
    }

    public function setPakietStart(\DateTimeInterface $pakiet_start): self
    {
        $this->pakiet_start = $pakiet_start;

        return $this;
    }

    public function getPakietEnd(): ?\DateTimeInterface
    {
        return $this->pakiet_end;
    }

    public function setPakietEnd(\DateTimeInterface $pakiet_end): self
    {
        $this->pakiet_end = $pakiet_end;

        return $this;
    }

    public function getPakietTime(): ?int
    {
        return $this->pakiet_time;
    }

    public function setPakietTime(int $pakiet_time): self
    {
        $this->pakiet_time = $pakiet_time;

        return $this;
    }

    public function getRestMin(): ?int
    {
        return $this->rest_min;
    }

    public function setRestMin(?int $rest_min): self
    {
        $this->rest_min = $rest_min;

        return $this;
    }

    public function getPakietTimeByYear(): ?int
    {
        return $this->pakiet_time_byYear;
    }

    public function setPakietTimeByYear(?int $pakiet_time_byYear): self
    {
        $this->pakiet_time_byYear = $pakiet_time_byYear;

        return $this;
    }

    public function getSettlement(): ?string
    {
        return $this->settlement;
    }

    public function setSettlement(string $settlement): self
    {
        $this->settlement = $settlement;

        return $this;
    }

    public function getStatusPakiet(): ?string
    {
        return $this->status_pakiet;
    }

    public function setStatusPakiet(string $status_pakiet): self
    {
        $this->status_pakiet = $status_pakiet;

        return $this;
    }
}
