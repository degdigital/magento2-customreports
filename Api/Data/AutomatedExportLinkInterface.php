<?php declare(strict_types=1);

namespace DEG\CustomReports\Api\Data;

use DEG\CustomReports\Model\AutomatedExportLink;

interface AutomatedExportLinkInterface
{
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';
    public const FIELD_CUSTOMREPORT_ID = 'customreport_id';
    public const FIELD_AUTOMATEDEXPORT_ID = 'automatedexport_id';

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
