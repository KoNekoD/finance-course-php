<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=8)
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private string $name;

    /**
     * @ORM\Column(type="float")
     */
    private float $rate;

    public function __construct(string $id, string $name, float $rate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rate = $rate;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
