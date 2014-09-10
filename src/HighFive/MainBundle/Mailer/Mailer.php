<?php

namespace HighFive\MainBundle\Mailer;

use Stampie\MailerInterface as StampieMailerInterface;

class Mailer implements MailerInterface
{
    private $mailer;
    private $twig;
    private $defaultSender;
    private $styleInliner;

    /**
     * @param StampieMailerInterface $mailer
     * @param \Twig_Environment      $twig
     * @param string                 $defaultSender
     * @param StyleInlinerInterface  $styleInliner
     */
    public function __construct(StampieMailerInterface $mailer, \Twig_Environment $twig, $defaultSender, StyleInlinerInterface $styleInliner)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->defaultSender = $defaultSender;
        $this->styleInliner = $styleInliner;
    }

    /**
     * {@inheritDoc}
     */
    public function send($recipient, $templateName, array $context = array(), $sender = null)
    {
        /** @var $template \Twig_Template */
        $template = $this->twig->loadTemplate($templateName);
        $context = $this->twig->mergeGlobals($context);

        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $htmlBody = $this->styleInliner->inlineStyle($htmlBody);

        $message = $this->createMessage($sender, $recipient, $subject, $textBody, $htmlBody);

        $this->mailer->send($message);
    }

    /**
     * Creates the message with the different parts.
     *
     * @param string $sender
     * @param string $recipient
     * @param string $subject
     * @param string $textBody
     * @param string $htmlBody
     *
     * @return \Stampie\MessageInterface
     */
    private function createMessage($sender, $recipient, $subject, $textBody, $htmlBody)
    {
        if (null === $sender) {
            $sender = $this->defaultSender;
        }
        $message = new MutableMessage();

        $message->setSubject($subject)
            ->setFrom($sender)
            ->setTo($recipient)
            ->setText($textBody);

        if (!empty($htmlBody)) {
            $message->setHtml($htmlBody);
        }

        return $message;
    }
}
