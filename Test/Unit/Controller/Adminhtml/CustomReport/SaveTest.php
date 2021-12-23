<?php

namespace DEG\CustomReports\Test\Unit\Controller\Adminhtml\CustomReport;

use DEG\CustomReports\Api\CustomReportRepositoryInterface;
use DEG\CustomReports\Controller\Adminhtml\CustomReport\Save;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use PHPUnit\Framework\TestCase;

class SaveTest extends TestCase
{
    /**
     * @var Save
     */
    protected $save;

    /**
     * @var Context|Mock
     */
    protected $context;

    /**
     * @var DataPersistorInterface|Mock
     */
    protected $dataPersistor;

    /**
     * @var CustomReportRepositoryInterface|Mock
     */
    protected $automatedExportRepository;

    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $redirectPageMock;

    /**
     * @var \Magento\Framework\ObjectManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $objectManagerMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $messageManagerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(Context::class);
        $this->requestMock = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $this->context->method('getRequest')->willReturn($this->requestMock);

        $this->redirectPageMock = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->context->method('getResultRedirectFactory')->willReturn($this->redirectPageMock);

        $this->objectManagerMock = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->context->method('getObjectManager')->willReturn($this->objectManagerMock);

        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->context->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->dataPersistor = $this->createMock(DataPersistorInterface::class);
        $this->automatedExportRepository = $this->createMock(CustomReportRepositoryInterface::class);
        $this->save = new Save($this->context, $this->dataPersistor, $this->automatedExportRepository);
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

    public function testExecute(): void
    {
        $data = [
            'customreport_id' => 1
        ];
        $this->requestMock->method('getPostValue')->willReturn($data);

        $redirectPageMock = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);
        $this->redirectPageMock->method('create')->willReturn($redirectPageMock);

        $customReportMock = $this->createMock(\DEG\CustomReports\Model\CustomReport::class);
        $this->objectManagerMock->method('create')->willReturn($customReportMock);

        $redirectPageMock->method('setPath')->willReturnSelf();

        $this->save->execute();
    }
}
