<?php

namespace App\Controller;

use App\Dto\SendNotificationRequest;
use App\Message\ProcessSendNotificationRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

        $messageBus->dispatch(new ProcessSendNotificationRequest($request));

        return new Response();
    }
}