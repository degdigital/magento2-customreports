<?php declare(strict_types=1);

namespace DEG\CustomReports\Api;

interface SendErrorEmailServiceInterface
{
    /**
     * @param string $errorEmailAddresses
     * @param array  $templateVars
     *
     * @return void
     */
    public function execute(string $errorEmailAddresses, array $templateVars);
}
