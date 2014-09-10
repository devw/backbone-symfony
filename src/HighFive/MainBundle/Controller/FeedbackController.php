<?php

namespace HighFive\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Controller managing the invitations
 */
class FeedbackController extends Controller
{
    /**
     * Sends the user feedback to the team.
     *
     * @param Request $request
     *
     * @throws HttpException
     * @return Response
     */
    public function sendAction(Request $request)
    {
        $message = $request->request->get('message');
        if (empty($message)) {
            throw new HttpException(412, 'The feedback message should not be empty');
        }

        $user = $this->getUser();

        /** @var $mailer \HighFive\MainBundle\Mailer\MailerInterface */
        $mailer = $this->get('high_five.mailer');
        $mailer->send(
            $this->container->getParameter('high_five.feedback.address'),
            'MainBundle:Mail:feedback.html.twig',
            array('message' => $message, 'sender' => $user),
            $user->getEmail()
        );

        return new Response('', 201);
    }
}
