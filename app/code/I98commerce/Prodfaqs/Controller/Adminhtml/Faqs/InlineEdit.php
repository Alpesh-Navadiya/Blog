<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use I98commerce\Prodfaqs\Model\Faqs as ModelFaqs;

class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;
    /**
     * @var ModelFaqs
     */
    protected $FaqsModel;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param ModelFaqs $FaqsModel
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        ModelFaqs $FaqsModel,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->FaqsModel = $FaqsModel;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Desc var
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('I98commerce_Prodfaqs::faqs');
    }

    /**
     * Desc
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /**
         * Short
         *
         * @var \Magento\Framework\Controller\Result\Json $resultJson
         */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $Id) {
            $Faqs = $this->FaqsModel->load($Id);
            try {
                $Data = $this->filterPost($postItems[$Id]);
                $this->validatePost($Data, $Faqs, $error, $messages);
                $extendedPageData = $Faqs->getData();
                $this->setFaqsData($Faqs, $extendedPageData, $Data);

                $this->FaqsModel->save($Faqs);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithId($Faqs, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithId($Faqs, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithId(
                    $Faqs,
                    __('Something went wrong while saving the item.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Short
     *
     * @param array $postData
     * @return array|mixed
     */
    protected function filterPost($postData = [])
    {
        //$pageData = $this->dataProcessor->filter($postData);
        return $postData;
    }

    /**
     * Desc
     *
     * @param array $pageData
     * @param ModelFaqs $page
     * @param text $error
     * @param array $messages
     * @return void
     */
    protected function validatePost(array $pageData, ModelFaqs $page, &$error, array &$messages)
    {
        if (!($this->dataProcessor->validate($pageData) && $this->dataProcessor->validateRequireEntry($pageData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithId($page, $error->getText());
            }
        }
    }

    /**
     * Desc
     *
     * @param ModelFaqs $page
     * @param $errorText
     * @return string
     */
    protected function getErrorWithId(ModelFaqs $page, $errorText)
    {
        return '[Page ID: ' . $page->getId() . '] ' . $errorText;
    }

    /**
     * Desc
     *
     * @param ModelFaqs $page
     * @param array $extendedPageData
     * @param array $pageData
     * @return $this
     */
    public function setFaqsData(ModelFaqs $page, array $extendedPageData, array $pageData)
    {
        $page->setData(array_merge($page->getData(), $extendedPageData, $pageData));
        return $this;
    }
}
