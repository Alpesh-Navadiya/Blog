<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Topic;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use I98commerce\Prodfaqs\Model\Topic;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\TestFramework\Inspection\Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\RequestInterface;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var Escaper
     */
    protected $_escaper;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var DateTimeFactory
     */
    protected $_dateFactory;
    /**
     * @var Topic
     */
    protected $_topic;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param Escaper $escaper
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param DateTimeFactory $dateFactory
     * @param Topic $topic
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory,
        \I98commerce\Prodfaqs\Model\Topic $topic
    ) {

        $this->dataPersistor = $dataPersistor;
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->_dateFactory = $dateFactory;
        $this->inlineTranslation = $inlineTranslation;
        $this->_topic = $topic;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('faqs_topic_id');

            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Block::STATUS_ENABLED;
            }
            if (empty($data['faqs_topic_id'])) {
                $data['faqs_topic_id'] = null;
            }

            /** @var \Magento\Cms\Model\Block $model */
            $model = $this->_topic->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Topic no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] ='/faqs/'.$data['image'][0]['name'];
            } else {
                $data['image'] = null;
            }
           // print_r($data);
           //exit;
            $model->setData($data);

            $this->inlineTranslation->suspend();
            try {
                    //////////////////// email
                $model->save();
                $this->messageManager->addSuccess(__('Topic Saved successfully'));
                $this->dataPersistor->clear('i98commerce_faqs_topic');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['faqs_topic_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the topic.'));
            }

            $this->dataPersistor->set('i98commerce_faqs_topic', $data);
            return $resultRedirect->setPath('*/*/edit', ['faqs_topic_id' => $this->getRequest()->getParam('faqs_topic_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
