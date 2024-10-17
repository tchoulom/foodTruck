<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $foodTruckName = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        // Génération d'un nouvel UUID lors de la création de la réservation
        $this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFoodTruckName(): ?string
    {
        return $this->foodTruckName;
    }

    public function setFoodTruckName(string $foodTruckName): self
    {
        $this->foodTruckName = $foodTruckName;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
