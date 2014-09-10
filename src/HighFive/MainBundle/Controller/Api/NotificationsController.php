<?php

namespace HighFive\MainBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NotificationsController extends Controller
{
    /**
     * Gets all notifications of the organization.
     *
     * @ApiDoc(resource=true)
     *
     * @return View
     */
    public function getNotificationsAction()
    {
        $notifications = $this->getNotificationRepository()->getUnreadNotifications($this->getUser());

        return View::create($notifications);
    }

    /**
     * Gets a notification.
     *
     * @ApiDoc(resource=true)
     * @Route(requirements={"id":"\d+"})
     *
     * @param int $id Id of the notification
     *
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     * @return View
     */
    public function getNotificationAction($id)
    {
        /** @var $notification \HighFive\MainBundle\Entity\Notification */
        $notification = $this->getNotificationRepository()->find($id);

        if (null === $notification) {
            throw new NotFoundHttpException(sprintf('The notification with the id %s does not exist', $id));
        }

        if ($notification->getRecipient() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        return View::create($notification);
    }

    /**
     * Marks a notification as read.
     *
     * @ApiDoc()
     * @Route (requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id      Id of the notification
     *
     * @throws AccessDeniedException
     * @throws HttpException
     * @throws NotFoundHttpException
     * @return View
     */
    public function patchNotificationAction(Request $request, $id)
    {
        /** @var $notification \HighFive\MainBundle\Entity\Notification */
        $notification = $this->getNotificationRepository()->find($id);

        if (null === $notification) {
            throw new NotFoundHttpException(sprintf('The notification with the id %s does not exist', $id));
        }

        if ($notification->getRecipient() !== $this->getUser()) {
            throw new AccessDeniedException();
        }

        if (!$request->request->has('read')) {
            throw new HttpException(400, 'The "read" property is missing');
        }

        $notification->setRead((boolean) $request->request->get('read'));

        $this->getDoctrine()->getManager()->flush();

        return View::create($notification);
    }

    /**
     * @return \HighFive\MainBundle\Doctrine\Repository\NotificationRepository
     */
    private function getNotificationRepository()
    {
        return $this->getDoctrine()->getRepository('MainBundle:Notification');
    }
}
