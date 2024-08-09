<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RankingRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RankingRepository::class)]
class Ranking implements Sortable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $country = null;

    #[ORM\Column]
    private ?int $gold = null;

    #[ORM\Column]
    private ?int $silver = null;

    #[ORM\Column]
    private ?int $bronze = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getGold(): ?int
    {
        return $this->gold;
    }

    public function setGold(int $gold): static
    {
        $this->gold = $gold;

        return $this;
    }

    public function getSilver(): ?int
    {
        return $this->silver;
    }

    public function setSilver(int $silver): static
    {
        $this->silver = $silver;

        return $this;
    }

    public function getBronze(): ?int
    {
        return $this->bronze;
    }

    public function setBronze(int $bronze): static
    {
        $this->bronze = $bronze;

        return $this;
    }
}
