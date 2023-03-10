<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\SendNotificationRequest;
use App\Message\ProcessSendNotificationRequest;
use App\Repository\NotificationRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/api/notifications', name: 'notifications_')]
final class NotificationsApiController extends AbstractFOSRestController
{
    #[Route('', methods: ['POST'], name: 'create')]
    #[ParamConverter('request', converter: 'fos_rest.request_body')]
    public function create(
        SendNotificationRequest $request,
        ConstraintViolationListInterface $validationErrors,
        MessageBusInterface $messageBus
    ): Response
    {
        if (count($validationErrors)) {
            return $this->json($validationErrors, Response::HTTP_BAD_REQUEST);    
        }

        // TO DO:
        // - validate only supported channels are requested
        // - validate all fields specific for different channels are set

        $messageBus->dispatch(new ProcessSendNotificationRequest($request));

        return new Response();
    }

    #[Route('/user/{userId}', methods: ['GET'], name: 'get_list')]
    public function getList(int $userId, NotificationRepository $notificationRepository): Response
    {
        return $this->json(
            $notificationRepository->findLastByUserAsArray($userId)
        );
    }
}