<?php declare(strict_types=1);

namespace DEG\CustomReports\Api\Data;

/**
 * @method int getId()
 * @method string getTitle()
 * @method string getCronExpr()
 * @method string|array getExportTypes()
 * @method string|array getFileTypes()
 * @method string getFilenamePattern()
 * @method string getExportLocation()
 * @method string getCreatedAt()
 * @method string getUpdatedAt()
 * @method string|array getCustomreportIds()
 * @method AutomatedExportInterface setId(int $id)
 * @method AutomatedExportInterface setTitle(string $title)
 * @method AutomatedExportInterface setCronExpr(string|array $cronExpr)
 * @method AutomatedExportInterface setExportTypes(string|array $exportTypes)
 * @method AutomatedExportInterface setFileTypes(string|array $fileTypes)
 * @method AutomatedExportInterface setFilenamePattern(string $filenamePattern)
 * @method AutomatedExportInterface setExportLocation(string $exportLocation)
 * @method AutomatedExportInterface setCreatedAt(string $createdAt)
 * @method AutomatedExportInterface setUpdatedAt(string $updatedAt)
 * @method AutomatedExportInterface setCustomreportIds(string|array $customreportIds)
 */
interface AutomatedExportInterface
{
}
