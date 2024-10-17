<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ReservationRepository $reservationRepository;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->validator = $validator;
    }

    #[Route('/reservation', methods: ['POST'])]
    public function addReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $foodTruckName = $data['foodTruckName'] ?? null;
        $date = isset($data['date']) ? new \DateTime($data['date']) : null;

        // Validate input
        if (!$foodTruckName || !$date) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        // Check if the food truck has already booked for the week
        if ($this->reservationRepository->hasFoodTruckBookedThisWeek($foodTruckName)) {
            return new JsonResponse(['error' => 'Food truck has already reserved this week'], Response::HTTP_CONFLICT);
        }

        $reservation = new Reservation();
        $reservation->setFoodTruckName($foodTruckName);
        $reservation->setDate($date);

        // Validate the reservation entity
        $errors = $this->validator->validate($reservation);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        // Persist and save the reservation
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Reservation added successfully'], Response::HTTP_CREATED);
    }

    #[Route('/reservation/{id}', methods: ['DELETE'])]
    public function deleteReservation(string $id): JsonResponse
    {
        // Cherchez la réservation par UUID
        $reservation = $this->reservationRepository->findOneBy(['id' => $id]);

        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Reservation deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/reservations/{date}', methods: ['GET'])]
    public function listReservationsByDay(string $date): JsonResponse
    {
        $parsedDate = new \DateTime($date);
        $reservations = $this->reservationRepository->findBy(['date' => $parsedDate]);

        $data = array_map(function (Reservation $reservation) {
            return [
                'id' => $reservation->getId(),
                'foodTruckName' => $reservation->getFoodTruckName(),
                'date' => $reservation->getDate()->format('Y-m-d'),
            ];
        }, $reservations);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
