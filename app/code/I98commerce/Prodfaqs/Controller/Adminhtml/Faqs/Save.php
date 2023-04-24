<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use I98commerce\Prodfaqs\Model\Faqs;
use Magento\Catalog\Model\Product;
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
     * @var Faqs
     */
    protected $_faqs;
    /**
     * @var Product
     */
    protected $_product;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param Escaper $escaper
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param DateTimeFactory $dateFactory
     * @param Faqs $faqs
     * @param Product $product
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory,
        \I98commerce\Prodfaqs\Model\Faqs $faqs,
        \Magento\Catalog\Model\Product $product
    ) {
        // $this->filter = $filter;
        // $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->_dateFactory = $dateFactory;
        $this->inlineTranslation = $inlineTranslation;
        $this->_faqs = $faqs;
        $this->_product = $product;
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
            $id = $this->getRequest()->getParam('faq_id');

            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Block::STATUS_ENABLED;
            }
            if (empty($data['faq_id'])) {
                $data['faq_id'] = null;
            }

            /** @var \Magento\Cms\Model\Block $model */
            $model = $this->_faqs->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Faq no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            if (isset($data["category_products"])) {
                $cat_array = json_decode($data['category_products'], true);

                $pro_array = array_values($cat_array);
                $c=0;
                foreach ($cat_array as $key => $value) {
                    $pro_array[$c] = $key;
                    $c++;
                }

                unset($data['category_products']);
                $data['product_id'] = $pro_array;

                foreach ($pro_array as $key => $value) {
                    $product = $this->_product->load($value);
                    $pro_arr[] = $product['name'];
                }
                if (isset($pro_arr)) {
                    $products_names = implode(',', $pro_arr);
                    $data['product_name'] = $products_names;
                }
            }

            $model->setData($data);

            $this->inlineTranslation->suspend();
            try {
                    //////////////////// email
                $model->save();
                $this->messageManager->addSuccess(__('Faq Saved successfully'));
                $this->dataPersistor->clear('i98commerce_faq');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['faq_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Faq.'));
            }

            $this->dataPersistor->set('i98commerce_faq', $data);
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $this->getRequest()->getParam('faq_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
