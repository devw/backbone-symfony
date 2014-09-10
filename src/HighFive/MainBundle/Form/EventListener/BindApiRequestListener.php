<?php

namespace HighFive\MainBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * Listener removing the extra fields from the data sent by Backbone to bind the form without extra data.
 */
class BindApiRequestListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        // High priority in order to supersede the core BindApiRequestListener
        return array(FormEvents::PRE_BIND => array('preBind', 255));
    }

    public function preBind(FormEvent $event)
    {
        $form = $event->getForm();

        /* @var Request $request */
        $request = $event->getData();

        // Only proceed if we actually deal with a Request
        if (!$request instanceof Request) {
            return;
        }

        $fields = array_keys($form->all());
        $data = array_intersect_key($request->request->all(), array_flip($fields));

        $event->setData($data);
    }
}
