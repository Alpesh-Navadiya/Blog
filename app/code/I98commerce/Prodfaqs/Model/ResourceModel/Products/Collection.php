<?php

namespace I98commerce\Prodfaqs\Model\ResourceModel\Products;

use \I98commerce\Prodfaqs\Model\ResourceModel\AbstractCollection;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Desc
     *
     * @var string
     */
    protected $_idFieldName = 'product_id';

    /**
     * @var $previewFlag
     */
    protected $_previewFlag;

    /**
     * Desc
     *
     * @param mixed $previewFlag
     */
    public function setPreviewFlag($previewFlag): void
    {
        $this->_previewFlag = $previewFlag;
    }

    /**
     * Desc
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('I98commerce\Prodfaqs\Model\Products', 'I98commerce\Prodfaqs\Model\ResourceModel\Products');

        $this->_map['fields']['product_id'] ='main_table.product_id';
    }

    /**
     * Desc
     *
     * @return Collection|void
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->group('product_id');
        $this->getSelect()->joinLeft(
            ['secondTable' => $this->getTable('catalog_product_entity')],
            'main_table.product_id = secondTable.entity_id',
            ['main_table.product_id','main_table.faq_id','secondTable.entity_id','secondTable.sku']
        );
        //echo $this->getSelect();exit;
    }

    /**
     * Desc
     *
     * @return Collection|void
     */
    protected function _afterLoad()
    {
      /*


       $items = $this->getColumnValues('product_id');

       echo "<pre>";
       print_r(array_unique($items));
      print_r($this->getData());exit;



        if (count($items)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(['cps' => $this->getTable('i98commerce_faq_answers')])
                    ->where('cps.faq_id IN (?)', $items);
            $result = $connection->fetchall($select);
               //echo $select;
          //  echo "<pre>";
           //    print_r($result);exit;
            if ($result) {

            //  $cms_idd = implode(',', $result);

            }



            foreach ($this as $item => $value) {


                echo $item;

                print_r($value->getData());
                }



            print_r(($this->getData()));exit;


        }


        $this->_previewFlag = false;
        return parent::_afterLoad();
        */
    }
}
