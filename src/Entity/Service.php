<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'services')]
    private Collection $employee;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: ClientService::class)]
    private Collection $clientServices;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->employee = new ArrayCollection();
        $this->clientServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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
            $appointment->setService($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getService() === $this) {
                $appointment->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployee(): Collection
    {
        return $this->employee;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employee->contains($employee)) {
            $this->employee->add($employee);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        $this->employee->removeElement($employee);

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
            $clientService->setService($this);
        }

        return $this;
    }

    public function removeClientService(ClientService $clientService): self
    {
        if ($this->clientServices->removeElement($clientService)) {
            // set the owning side to null (unless already changed)
            if ($clientService->getService() === $this) {
                $clientService->setService(null);
            }
        }

        return $this;
    }
}
