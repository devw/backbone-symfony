<?php

namespace HighFive\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontendBundle::index.html.twig');
    }

    public function testAction()
    {
        return $this->render('FrontendBundle::test.html.twig');
    }
}
