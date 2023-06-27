<?php

namespace App\Service;

use App\Repository\EmployeeRepository;
use App\Repository\AvailabilityRepository;
use DateInterval;

class AvailabilityTimeSlot
{
    private $employeeRepository;
    private $availabilityRepository;

    public function __construct(EmployeeRepository $employeeRepository, AvailabilityRepository $availabilityRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->availabilityRepository = $availabilityRepository;
    }

    public function getAvailableSlots(int $employeeId, string $day_of_week, int $duration): array
    {
        $employee = $this->employeeRepository->find($employeeId);

        if (!$employee) {
            throw new \Exception('Employé non trouvé');
        }

        $availabilities = $this->availabilityRepository->findBy(['employee' => $employee, 'day_of_week' => $day_of_week]);

        $slots = [];
        foreach ($availabilities as $availability) {
            $startAt = $availability->getStartAt();
            $endAt = $availability->getEndAt();
        
            while ($startAt <= $endAt) {
                // Ajoute le créneau horaire actuel à la liste des créneaux disponibles
                $slots[] = clone $startAt;
        
                // Passe au prochain créneau horaire
                $interval = new DateInterval('PT' . $duration . 'M');
                $startAt->add($interval);
            }
        
            // supprime le dernier créneau s'il dépasse $endAt
            if ($startAt > $endAt) {
                array_pop($slots);
            }
        }
        
        // convertis les objets DateTime en strings pour faciliter leur manipulation
        $slots = array_map(function ($date) {
            return $date->format('H:i');
        }, $slots);

        return $slots;
    }
}
