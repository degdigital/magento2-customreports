<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\Service;

use DEG\CustomReports\Api\ConfigProviderInterface;
use DEG\CustomReports\Api\SendErrorEmailServiceInterface;
use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Psr\Log\LoggerInterface;

class SendErrorEmailService implements SendErrorEmailServiceInterface
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected TransportBuilder $emailTransportBuilder;

    /**
     * @var \DEG\CustomReports\Api\ConfigProviderInterface
     */
    private ConfigProviderInterface $configProvider;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(
        TransportBuilder $emailTransportBuilder,
        ConfigProviderInterface $configProvider,
        LoggerInterface $logger
    ) {
        $this->emailTransportBuilder = $emailTransportBuilder;
        $this->configProvider = $configProvider;
        $this->logger = $logger;
    }

    /**
     * @param string $errorEmailAddresses
     * @param array  $templateVars
     *
     * @return void
     */
    public function execute(string $errorEmailAddresses, array $templateVars)
    {
        try {
            $this->emailTransportBuilder
                ->setTemplateIdentifier($this->configProvider->getErrorEmailTemplateId())
                ->setTemplateOptions(['area' => Area::AREA_ADMINHTML, 'store' => 0])
                ->setTemplateVars($templateVars)
                ->setFromByScope($this->configProvider->getEmailFrom());
            $errorEmailsArray = explode(',', $errorEmailAddresses);
            foreach ($errorEmailsArray as $email) {
                $this->emailTransportBuilder->addTo($email);
            }
            $this->emailTransportBuilder->getTransport()->sendMessage();
        } catch (Exception $exception) {
            $this->logger->critical($exception);
        }
    }
}
