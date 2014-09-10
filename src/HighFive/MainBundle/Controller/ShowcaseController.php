<?php

namespace HighFive\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller displaying the showcase
 */
class ShowcaseController extends Controller
{
    public function indexAction()
    {
        if (null !== $this->getUser()) {
            return new RedirectResponse($this->generateUrl('frontend'));
        }

        $form = $this->container->get('fos_user.registration.form');

        return $this->render('MainBundle:Showcase:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
