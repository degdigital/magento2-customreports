<?php declare(strict_types=1);

namespace DEG\CustomReports\Api\Data;

use DEG\CustomReports\Model\AutomatedExportLink;

interface AutomatedExportLinkInterface
{
    public function getId();

    public function getCustomreportId();

    public function getAutomatedexportId(): int;

    public function getCreatedAt(): ?string;

    public function getUpdatedAt(): ?string;

    public function setId($value);

    public function setCustomreportId(int $customreportId): AutomatedExportLink;

    public function setAutomatedexportId(int $automatedexportId): AutomatedExportLink;

    public function setCreatedAt(string $createdAt): AutomatedExportLink;

    public function setUpdatedAt(string $updatedAt): AutomatedExportLink;
}
