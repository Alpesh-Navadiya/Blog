<?php
namespace Know\Module\Block;

/*
 * Webkul Hello Block
 */

use Magento\Store\Model\ScopeInterface;

class KMComponent extends \Magento\Framework\View\Element\Template
{
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * getContentForDisplay
     * @return array
     */
    public function getConfigArray()
    {
        return[
           'title'=> $this->_scopeConfig->getValue("knowthemage/general/title",ScopeInterface::SCOPE_STORE),
            'content'=> $this->_scopeConfig->getValue("knowthemage/general/content",ScopeInterface::SCOPE_STORE),
            'btn_txt'=> $this->_scopeConfig->getValue("knowthemage/general/btn_txt",ScopeInterface::SCOPE_STORE),
            'is_primary_btn'=> $this->_scopeConfig->getValue("knowthemage/general/is_primary_btn",ScopeInterface::SCOPE_STORE),
            'is_active'=> $this->_scopeConfig->getValue("knowthemage/general/is_active",ScopeInterface::SCOPE_STORE)

        ];
    }
}
