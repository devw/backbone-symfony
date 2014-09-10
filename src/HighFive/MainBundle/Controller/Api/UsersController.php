<?php

namespace HighFive\MainBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UsersController extends Controller
{
    /**
     * Gets all users of the organization.
     *
     * @ApiDoc(resource=true)
     *
     * @return View
     */
    public function getUsersAction()
    {
        $organization = $this->getUser()->getOrganization();

        $users = $this->getUserRepository()->findByOrganization($organization->getId());

        return View::create($users);
    }

    /**
     * Gets a user.
     *
     * @ApiDoc(resource=true)
     * @Route(requirements={"id":"\d+"})
     *
     * @param int $id Id of the user
     *
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     * @return View
     */
    public function getUserAction($id)
    {
        $user = $this->getUserRepository()->find($id);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with the id %s does not exist', $id));
        }

        if ($user->getOrganization() !== $this->getUser()->getOrganization()) {
            throw new AccessDeniedException();
        }

        return View::create($user);
    }

    /**
     * Gets the board of most recognized users in the organization.
     *
     * @ApiDoc(resource=true)
     *
     * @return View
     */
    public function getUsersBoardAction()
    {
        $organization = $this->getUser()->getOrganization();

        $users = $this->getUserRepository()->getBoard($organization);

        return View::create($users);
    }

    /**
     * Gets the board of most recognized users in the organization for the current week.
     *
     * @ApiDoc(resource=true)
     * @Route(pattern="/users/weekly-board")
     *
     * @return View
     */
    public function getUsersWeeklyBoardAction()
    {
        $organization = $this->getUser()->getOrganization();

        $users = $this->getUserRepository()->getWeeklyBoard($organization);

        return View::create($users);
    }

    /**
     * Gets the board of most recognized users in the organization for the current month.
     *
     * @ApiDoc(resource=true)
     * @Route(pattern="/users/monthly-board")
     *
     * @return View
     */
    public function getUsersMonthlyBoardAction()
    {
        $organization = $this->getUser()->getOrganization();

        $users = $this->getUserRepository()->getMonthlyBoard($organization);

        return View::create($users);
    }

    /**
     * @return \HighFive\MainBundle\Doctrine\Repository\UserRepository
     */
    private function getUserRepository()
    {
        return $this->getDoctrine()->getRepository('MainBundle:User');
    }
}
