<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

/**
 * Provides custom reports configuration
 */
interface ConfigProviderInterface
{
    /**
     * Return the template id to be used for error emails
     *
     * @return string
     */
    public function getErrorEmailTemplateId(): string;

    /**
     * Return the "from" email to use when sending notifications
     *
     * @return array
     */
    public function getEmailFrom(): array;
}
