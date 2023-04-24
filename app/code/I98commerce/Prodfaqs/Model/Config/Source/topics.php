<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace I98commerce\Prodfaqs\Model\Config\Source;

class topics extends \I98commerce\Prodfaqs\Model\Topic implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Des
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->gettopics();
        //return [['value' => 'all', 'label' => __('All')],
              //  ['value' => 'guests', 'label' => __('Only Guests')],
              //  ['value' => 'registered', 'label' => __('Only Registered')],
              //  ['value' => 'none', 'label' => __('None')]];
    }
}
