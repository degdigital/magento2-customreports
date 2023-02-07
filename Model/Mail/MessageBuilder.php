<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Mail;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\EmailMessageInterface;
use Magento\Framework\Mail\EmailMessageInterfaceFactory;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;

class MessageBuilder
{
    protected EmailMessageInterface $originalMessage;

    protected array $attachmentParts = [];

    public function __construct(
        protected EmailMessageInterfaceFactory $emailMessageInterfaceFactory,
        protected MimeMessageInterfaceFactory $mimeMessageInterfaceFactory
    ) {
    }

    /**
     * @return EmailMessageInterface
     * @throws LocalizedException
     */
    public function build(): EmailMessageInterface
    {
        $messageWithAttachments = [
            'to' => $this->originalMessage->getTo(),
            'from' => $this->originalMessage->getFrom(),
            'subject' => $this->originalMessage->getSubject(),
            'body' => $this->mimeMessageInterfaceFactory->create(
                ['parts' => array_merge($this->originalMessage->getBody()->getParts(), $this->attachmentParts)]
            ),
        ];

        return $this->emailMessageInterfaceFactory->create($messageWithAttachments);
    }

    /**
     * @param EmailMessageInterface $originalMessage
     * @return MessageBuilder
     */
    public function setOriginalMessage(EmailMessageInterface $originalMessage): static
    {
        $this->originalMessage = $originalMessage;

        return $this;
    }

    /**
     * @param array $attachmentParts
     * @return MessageBuilder
     */
    public function setAttachmentParts(array $attachmentParts): static
    {
        $this->attachmentParts = $attachmentParts;

        return $this;
    }
}
