<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace I98commerce\Prodfaqs\Block\Adminhtml\Faqs\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class BackButton
 */
class Select extends GenericButton implements ButtonProviderInterface
{
    /**
     * Desc
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Select'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'type'=> 'select',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }
}
