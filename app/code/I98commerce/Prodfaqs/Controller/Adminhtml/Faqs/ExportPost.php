<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Controller\Adminhtml\Faqs;

use I98commerce\Prodfaqs\Model\ResourceModel\Faqs\Collection;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportPost extends \Magento\Backend\App\Action
{
    /**
     * Short desc
     *
     * @var FileFactory
     */
    protected $fileFactory;
    /**
     * Short desc
     *
     * @var Collection
     */
    protected $_faqsCollection;

    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param Collection $faqsCollection
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \I98commerce\Prodfaqs\Model\ResourceModel\Faqs\Collection $faqsCollection
    ) {
        $this->fileFactory = $fileFactory;
        $this->_faqsCollection = $faqsCollection;
        parent::__construct($context);
    }

    /**
     * Export action from import/export
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        /** start csv content and set template */
        $headers = new \Magento\Framework\DataObject(
            [
                'title' => __('Question'),
                'a_answer' => __('Answer'),
                'identifier' => __('Identifier'),
                'tags' => __('Tags'),
                'status' => __('Status'),
                'show_on_main' => __('Show on main page'),
                'faq_order' => __('Faq Order'),
                't_title' => __('Topic Title'),
                't_identifier' => __('Topic identifier'),
                't_status' => __('Topic Status'),
                't_show_on_main' => __('Topic show on main page'),
                't_topic_order' => __('Topic Order'),
                'store_id' => __('Store IDs'),
            ]
        );

        $template = '"{{title}}","{{a_answer}}","{{identifier}}","{{tags}}","{{status}}"' .
            ',"{{show_on_main}}","{{faq_order}}","{{t_title}}","{{t_identifier}}","{{t_status}}","{{t_show_on_main}}","{{t_topic_order}}","{{store_id}}"';
        $content = $headers->toString($template);
        $content .= "\n";

        $collection = $this->_faqsCollection->joinTopicTable()->joinStoreTable()->joinAnswerTable();
        while ($faq = $collection->fetchItem()) {
            $content .= $faq->toString($template) . "\n";
        }
        return $this->fileFactory->create('Prodfaqs.csv', $content, DirectoryList::VAR_DIR);
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
