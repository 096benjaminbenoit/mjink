<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $fullname = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: ClientService::class)]
    private Collection $clientServices;

    public function __toString()
    {
        return $this->first_name;
    }
    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->clientServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setClient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getClient() === $this) {
                $appointment->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClientService>
     */
    public function getClientServices(): Collection
    {
        return $this->clientServices;
    }

    public function addClientService(ClientService $clientService): self
    {
        if (!$this->clientServices->contains($clientService)) {
            $this->clientServices->add($clientService);
            $clientService->setClient($this);
        }

        return $this;
    }

    public function removeClientService(ClientService $clientService): self
    {
        if ($this->clientServices->removeElement($clientService)) {
            // set the owning side to null (unless already changed)
            if ($clientService->getClient() === $this) {
                $clientService->setClient(null);
            }
        }

        return $this;
    }
}
