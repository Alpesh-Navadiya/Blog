<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\TaxImportExport\Model\Rate\CsvImportHandler;

class ImportPost extends \Magento\Backend\App\Action
{

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param CsvImportHandler $CsvImportHandler
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\TaxImportExport\Model\Rate\CsvImportHandler $CsvImportHandler
    ) {
        $this->fileFactory = $fileFactory;
        $this->_CsvImportHandler = $CsvImportHandler;
        parent::__construct($context);
    }

    /**
     * Import action from import/export tax
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        if ($this->getRequest()->isPost() && !empty($this->getRequest()->getFiles('import_faqs_file'))) {
            try {
                $this->_CsvImportHandler->importFromCsvFile($this->getRequest()->getFiles('import_faqs_file'));

                $this->messageManager->addSuccess(__('The faqs have been imported.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Invalid file upload attempt'));
            }
        } else {
            $this->messageManager->addError(__('Invalid file upload attempt.'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }

     /**
      * Short desc
      *
      * @return bool
      */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('I98commerce_Prodfaqs::importexport');
    }
}
