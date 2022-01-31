<?php declare(strict_types=1);

namespace DEG\CustomReports\Model\AutomatedExport\ExportType\Stream;

use Magento\Framework\DataObject;
use Magento\Framework\Filesystem\File\WriteInterface;

/**
 * @method string getFilename()
 * @method string getFilepath()
 * @method string getFileType()
 * @method WriteInterface getStream()
 * @method LocalFileStream setFilepath(string $filepath)
 * @method LocalFileStream setFilename(string $filename)
 * @method LocalFileStream setFileType(string $fileType)
 * @method LocalFileStream setStream(WriteInterface $stream)
 */
class LocalFileStream extends DataObject
{
}
