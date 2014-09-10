<?php

namespace HighFive\MainBundle\Controller\Api;

use FOS\Rest\Util\Codes;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MessagesController extends Controller
{
    /**
     * Gets all messages of the organization.
     *
     * @ApiDoc(resource=true)
     *
     * @return View
     */
    public function getMessagesAction()
    {
        $organization = $this->getUser()->getOrganization();

        $messages = $this->getMessageRepository()->getMessagesByOrganization($organization);

        return View::create($messages);
    }

    /**
     * Gets a message.
     *
     * @ApiDoc(resource=true)
     * @Route(requirements={"id":"\d+"})
     *
     * @param int $id Id of the message
     *
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     * @return View
     */
    public function getMessageAction($id)
    {
        /** @var $message \HighFive\MainBundle\Entity\Message */
        $message = $this->getMessageRepository()->find($id);

        if (null === $message) {
            throw new NotFoundHttpException(sprintf('The message with the id %s does not exist', $id));
        }

        if ($message->getOrganization() !== $this->getUser()->getOrganization()) {
            throw new AccessDeniedException();
        }

        return View::create($message);
    }

    /**
     * Posts a new announcement.
     *
     * @ApiDoc(
     *  formType="announcement_api"
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postMessagesAction(Request $request)
    {
        $manager = $this->getMessageManager();
        $message = $manager->createAnnouncement($this->getUser());

        $form = $this->createForm('announcement_api', $message);

        $form->bind($request);

        if ($form->isValid()) {
            $manager->saveMessage($message);

            return View::create($message, Codes::HTTP_CREATED);
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Gets the replies to a message.
     *
     * @ApiDoc(resource=true)
     * @Route(requirements={"id":"\d+"})
     *
     * @param int $id Id of the message
     *
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     * @return View
     */
    public function getMessageRepliesAction($id)
    {
        $repo = $this->getMessageRepository();
        /** @var $message \HighFive\MainBundle\Entity\Message */
        $message = $repo->find($id);

        if (null === $message) {
            throw new NotFoundHttpException(sprintf('The message with the id %s does not exist', $id));
        }

        if ($message->getOrganization() !== $this->getUser()->getOrganization()) {
            throw new AccessDeniedException();
        }

        $messages = $repo->getReplies($message);

        return View::create($messages);
    }

    /**
     * Replies to a message.
     *
     * @ApiDoc(
     *  formType="reply_api"
     * )
     * @Route(requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id      Id of the message
     *
     * @throws AccessDeniedException
     * @throws HttpException
     * @throws NotFoundHttpException
     * @return View
     */
    public function postMessageRepliesAction(Request $request, $id)
    {
        $manager = $this->getMessageManager();
        $parent = $manager->find($id);

        if (null === $parent) {
            throw new NotFoundHttpException(sprintf('The message with the id %s does not exist', $id));
        }

        if ($parent->getOrganization() !== $this->getUser()->getOrganization()) {
            throw new AccessDeniedException();
        }

        if (null !== $parent->getParent()) {
            throw new HttpException(412, sprintf('The message with the id %s is already a reply', $id));
        }

        $message = $manager->createReply($this->getUser(), $parent);

        $form = $this->createForm('reply_api', $message);

        $form->bind($request);

        if ($form->isValid()) {
            $manager->saveReply($message);

            return View::create($message, Codes::HTTP_CREATED);
        }

        return View::create($form, Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @return \HighFive\MainBundle\Model\MessageManager
     */
    private function getMessageManager()
    {
        return $this->get('high_five.messages.manager');
    }

    /**
     * @return \HighFive\MainBundle\Doctrine\Repository\MessageRepository
     */
    private function getMessageRepository()
    {
        return $this->getDoctrine()->getRepository('MainBundle:Message');
    }
}
