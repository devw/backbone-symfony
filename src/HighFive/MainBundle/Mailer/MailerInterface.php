<?php

namespace HighFive\MainBundle\Mailer;

interface MailerInterface
{
    /**
     * Sends a mail to the recipient(s) using the template.
     *
     * The template must define 3 blocks: subject, body_text and body_html.
     *
     * @param string|array $recipient    The recipient address
     * @param string       $templateName The name of the Twig template
     * @param array        $context      The context passed to the template
     * @param string       $sender       The sender address if the default one should not be used
     */
    public function send($recipient, $templateName, array $context = array(), $sender = null);
}
