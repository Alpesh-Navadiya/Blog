<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace I98commerce\Prodfaqs\Model\Config\Source;

class visibility implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Desc
     *
     * @return array[]
     */
    public function toOptionArray()
    {

        return [['value' => '1', 'label' => __('Public')],
                ['value' => '0', 'label' => __('Private')]];
              //  ['value' => 'registered', 'label' => __('Only Registered')],
              //  ['value' => 'none', 'label' => __('None')]];
    }
}
