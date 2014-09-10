<?php

namespace HighFive\MainBundle\Controller\Api;

use FOS\RestBundle\View\View;
use FOS\Rest\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RecognitionsController extends Controller
{
    /**
     * Creates a new recognition.
     *
     * @ApiDoc(
     *  formType="recognition_api"
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postRecognitionsAction(Request $request)
    {
        $manager = $this->getMessageManager();
        $message = $manager->createRecognitionMessage($this->getUser());

        $form = $this->createForm('recognition_api', $message);

        $form->bind($request);

        if ($form->isValid()) {
            $manager->saveMessage($message);

            return View::create(null, Codes::HTTP_NO_CONTENT);
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
}
