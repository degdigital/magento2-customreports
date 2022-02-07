<?php
declare(strict_types=1);

namespace DEG\CustomReports\Model;

use DEG\CustomReports\Api\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider implements ConfigProviderInterface
{
    protected const CONFIG_PATH_ERROR_EMAIL_TEMPLATE = 'deg_customreports/automated_exports/error_email_template';
    protected const CONFIG_PATH_EMAIL = 'trans_email/ident_general';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getErrorEmailTemplateId(): string
    {
        return $this->scopeConfig->getValue(static::CONFIG_PATH_ERROR_EMAIL_TEMPLATE) ?? '';
    }

    public function getEmailFrom(): array
    {
        return $this->scopeConfig->getValue(static::CONFIG_PATH_EMAIL);
    }
}
