<?php
declare(strict_types=1);
/** @noinspection DuplicatedCode */

/** @noinspection MessDetectorValidationInspection */

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\AutomatedExport;

use DEG\CustomReports\Api\AutomatedExportRepositoryInterface;
use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Api\Data\AutomatedExportInterfaceFactory;
use DEG\CustomReports\Controller\Adminhtml\AutomatedExport\Save;
use DEG\CustomReports\Model\AutomatedExport;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{
    /**
     * @var Save
     */
    protected Save $save;

    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var DataPersistorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $dataPersistor;

    /**
     * @var CustomReportRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportRepository;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $redirectPageFactoryMock;

    /**
     * @var \Magento\Framework\ObjectManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $objectManagerMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $messageManagerMock;

    /**
     * @var AutomatedExportInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $automatedExportFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->requestMock = $this->createMock(Http::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);

        $this->redirectPageFactoryMock = $this->createMock(RedirectFactory::class);
        $this->context->method('getResultRedirectFactory')->willReturn($this->redirectPageFactoryMock);

        $this->objectManagerMock = $this->createMock(ObjectManagerInterface::class);
        $this->context->method('getObjectManager')->willReturn($this->objectManagerMock);

        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->context->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->dataPersistor = $this->createMock(DataPersistorInterface::class);
        $this->automatedExportRepository = $this->createMock(AutomatedExportRepositoryInterface::class);
        $this->automatedExportFactory = $this->createMock(AutomatedExportInterfaceFactory::class);
        $this->save = new Save(
            $this->context,
            $this->dataPersistor,
            $this->automatedExportRepository,
            $this->automatedExportFactory
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->save);
        unset($this->context);
        unset($this->dataPersistor);
        unset($this->automatedExportRepository);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function testExecute(): void
    {
        $data = [
            'customreport_id' => 1,
        ];
        $this->requestMock->method('getPostValue')->willReturn($data);

        $redirectPageMock = $this->createMock(Redirect::class);
        $this->redirectPageFactoryMock->method('create')->willReturn($redirectPageMock);

        $customReportMock = $this->createMock(AutomatedExport::class);
        $this->automatedExportFactory->method('create')->willReturn($customReportMock);

        $redirectPageMock->method('setPath')->willReturnSelf();

        $this->save->execute();
    }
}
