<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Mail\Template;

use DEG\CustomReports\Model\Mail\MessageBuilderFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\AddressConverter;
use Magento\Framework\Mail\EmailMessageInterface;
use Magento\Framework\Mail\EmailMessageInterfaceFactory;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Magento\Framework\Mail\MimePartInterfaceFactory;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder as MagentoTransportBuilder;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Zend_Mime;

class TransportBuilder extends MagentoTransportBuilder
{
    protected array $attachmentParts = [];

    public function __construct(
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory,
        protected MessageBuilderFactory $messageBuilderFactory,
        protected MimePartInterfaceFactory $mimePartFactory,
        MessageInterfaceFactory $messageFactory = null,
        EmailMessageInterfaceFactory $emailMessageInterfaceFactory = null,
        MimeMessageInterfaceFactory $mimeMessageInterfaceFactory = null,
        MimePartInterfaceFactory $mimePartInterfaceFactory = null,
        AddressConverter $addressConverter = null
    ) {
        parent::__construct(
            $templateFactory,
            $message,
            $senderResolver,
            $objectManager,
            $mailTransportFactory,
            $messageFactory,
            $emailMessageInterfaceFactory,
            $mimeMessageInterfaceFactory,
            $mimePartInterfaceFactory,
            $addressConverter
        );
    }

    /**
     * @param string $body
     * @param string $filename
     * @param string $mimeType
     * @param string $disposition
     * @param string $encoding
     * @return $this
     */
    public function addAttachment(
        string $body,
        string $filename,
        string $mimeType = Zend_Mime::TYPE_OCTETSTREAM,
        string $disposition = Zend_Mime::DISPOSITION_ATTACHMENT,
        string $encoding = Zend_Mime::ENCODING_BASE64
    ): static {
        $this->attachmentParts[] = $this->mimePartFactory->create(
            [
                'content' => $body,
                'fileName' => $filename,
                'type' => $mimeType,
                'disposition' => $disposition,
                'encoding' => $encoding,
            ]
        );

        return $this;
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function prepareMessage(): static
    {
        parent::prepareMessage();

        /** @var EmailMessageInterface $oldMessage */
        $oldMessage = $this->message;
        $messageBuilder = $this->messageBuilderFactory->create();
        $this->message = $messageBuilder
            ->setOriginalMessage($oldMessage)
            ->setAttachmentParts($this->attachmentParts)
            ->build();

        return $this;
    }
}
