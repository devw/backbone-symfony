<?php

namespace HighFive\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller used to run code that is not used from the web normally.
 *
 * This allows to have the profiler and the detailed error output as the console
 * does not trigger the profiler.
 */
class DebugController extends Controller
{
    public function indexAction(Request $request)
    {
        /** @var $user \HighFive\MainBundle\Entity\User */
        $user = $this->getUser();
        /** @var $repo \HighFive\MainBundle\Doctrine\Repository\MessageRepository */
        $repo = $this->getDoctrine()->getRepository('MainBundle:Message');

//        $results = $repo->getTopMessages($user->getOrganization(), 15);
//        foreach ($results as $result) {
//            /** @var $message \HighFive\MainBundle\Entity\Message */
//            $message = $result;
//            var_dump(array($message->getMessage(), $message->getCreatedAt()->format('Y-m-d')));
//        }

        $this->get('high_five.notification.summary_sender')->sendSummary($user->getOrganization());

        return new Response('<html><body>Hello</body></html>');
    }
}
