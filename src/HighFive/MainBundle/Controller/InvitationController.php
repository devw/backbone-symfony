<?php

namespace HighFive\MainBundle\Controller;

use HighFive\MainBundle\HighFiveEvents;
use HighFive\MainBundle\Entity\User;
use HighFive\MainBundle\Event\UserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * Controller managing the invitations
 */
class InvitationController extends Controller
{
    /**
     * Sends invitations to some email addresses.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function inviteAction(Request $request)
    {
        $form = $this->createForm('high_five_invitation', array_fill(0, 3, ''));
        $templateSuffix = $request->isXmlHttpRequest() ? '_ajax' : '';

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                /** @var $manager \HighFive\MainBundle\Model\InvitationManager */
                $manager = $this->get('high_five.invitation.manager');

                $result = $manager->sendInvitations(array_filter($form->getData()), $this->getUser());

                return $this->render(sprintf('MainBundle:Invitation:sent%s.html.twig', $templateSuffix), $result);
            }
        }

        return $this->render(sprintf('MainBundle:Invitation:invite%s.html.twig', $templateSuffix), array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Registers using an invitation token.
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function registerAction(Request $request, $token)
    {
        /** @var $registry \Doctrine\Common\Persistence\ManagerRegistry */
        $registry = $this->getDoctrine();
        /** @var $invitationRepo \HighFive\MainBundle\Doctrine\Repository\InvitationRepository */
        $invitationRepo = $registry->getRepository('MainBundle:Invitation');
        $invitation = $invitationRepo->findOneByToken($token);

        if (null === $invitation) {
            throw new NotFoundHttpException(sprintf('The invitation with the token "%s" does not exist', $token));
        }

        $user = $invitation->getRecipient();
        $form = $this->createForm('high_five_partial_registration', $user);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $invitation->setConfirmedAt(new \DateTime());
                $user->setEnabled(true);
                $em = $registry->getManager();
                $em->persist($user);

                /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(HighFiveEvents::REGISTRATION_INVITED, new UserEvent($user));

                $em->flush();

                $request->getSession()->setFlash('fos_user_success', 'registration.flash.user_created');

                $response = new RedirectResponse($this->generateUrl('frontend'));
                $this->authenticateUser($user, $response);

                return $response;
            }
        }

        return $this->render('MainBundle:Invitation:register.html.twig', array(
            'token' => $token,
            'form' => $form->createView(),
            'invitation' => $invitation,
        ));
    }

    /**
     * @return Response
     */
    public function invitationNoCoworkersAction()
    {
        $form = $this->createForm('high_five_invitation', array_fill(0, 3, ''));

        return $this->render('MainBundle:Invitation:inviteNoCoworkers_ajax.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Authenticates a user with Symfony Security.
     *
     * @param UserInterface $user
     * @param Response      $response
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response
            );
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
