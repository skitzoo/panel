<?php

namespace App\Controller;

use App\Entity\BookingInfos;
use App\Form\BookingEditType;
use App\Repository\BookingInfosRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/Bookings/List/Page/{page}", name="Booking_List", requirements={"page" = "\d+"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int $page
     * @return array
     */
    public function Booking_List(BookingInfosRepository $bookingInfosRepository, $page): Response
    {
        $bookingsPerPage = 10;

        $bookings = $bookingInfosRepository->getBookings($page, $bookingsPerPage);

        $pagination = [
            'totalPages' => ceil(count($bookings) / $bookingsPerPage),
            'page' => $page
        ];

        return $this->render('booking/index.html.twig', [
            'pagination' => $pagination,
            'title' => 'Liste des réservations',
            'bookings' => $bookings
        ]);
    }

    /**
     * @Route("/Bookings/{id}/Edit", name="Booking_Edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Booking_Edit(BookingInfos $bookingInfos, BookingInfosRepository $bookingInfosRepository, EntityManagerInterface $entityManager, ParameterBagInterface $params, Request $request)
    {
        $eveningStartAt = \DateTime::createFromFormat('H:i', $params->get('orderEveningHours')[0]);
        $eveningFinishAt = \DateTime::createFromFormat('H:i', $params->get('orderEveningHours')[1]);

        $hours = [];

        while ($eveningStartAt <= $eveningFinishAt)
        {
            $hours[] = $eveningStartAt->format('H:i');
            $eveningStartAt->modify('+ '.$params->get('commandInterval').' Minute');
        }

        $bookings = $bookingInfosRepository->findAll();
        $disabledHours = [];

        foreach ($bookings as $row)
            $disabledHours[] = $row->getScheduleAt();

        $available = array_diff($hours, $disabledHours);

        $form = $this->createForm(BookingEditType::class, $bookingInfos, [
            'hours' => $available
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $scheduleAt = $form->get('scheduleAt')->getData();

            $check = $bookingInfosRepository->findBy(['scheduleAt' => $scheduleAt]);

            if ($check !== null)
                $this->addFlash('danger', 'Cet horaire est déjà réservé');
            else {
                $entityManager->persist($bookingInfos);
                $entityManager->flush();

                $this->addFlash('success', 'L\'horaire de réservation a été modifié');

                return $this->redirectToRoute('Booking_List', ['page' => 1]);
            }
        }

        return $this->render('booking/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Editer une réservation'
        ]);
    }

    /**
     * @Route("/Bookings/{id}/Delete", name="Booking_Delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Booking_Delete($id, Request $request, BookingInfosRepository $bookingInfosRepository, EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        $booking = $bookingInfosRepository->findOneBy(['id' => $id]);

        if ($booking !== null)
        {
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        $this->addFlash('success', "La réservation a été supprimée avec succès");

        return $this->redirectToRoute('Booking_List', ['page' => 1]);
    }
}
