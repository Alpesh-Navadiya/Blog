<?php


namespace I98commerce\Prodfaqs\Ui\Component\Listing\Column\Products;

use I98commerce\Prodfaqs\Model\Answers;
use I98commerce\Prodfaqs\Model\Products;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * @method Avatar setName($name)
 */
class Product extends Column
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;
    /**
     * @var Products
     */
    protected $_productsModel;
    /**
     * @var \Sample\News\Model\Uploader
     */
    protected $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Answers $answersModel
     * @param Products $productModel
     * @param \Magento\Catalog\Model\Product $product
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \I98commerce\Prodfaqs\Model\Answers $answersModel,
        \I98commerce\Prodfaqs\Model\Products $productModel,
        \Magento\Catalog\Model\Product $product,
        array $components = [],
        array $data = []
    ) {

        $this->urlBuilder = $urlBuilder;
        $this->_productsModel = $productModel;
        $this->_product = $product;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $url = $this->urlBuilder->getUrl('prodfaqs/faqs/filters');

            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {

                $suburl = $url.'?id='.$item['product_id'];
                $product = $this->_product->load($item['product_id']);
                 $item[$fieldName] =  ("<a  onclick=\"window.location='$suburl'\" href='$suburl' >".$product['name']."</a>");
                $item['sku'] = $product['sku'];
                $item['questions'] = count($this->_productsModel->CountProductFaq($item['product_id']));

                $item['visiblequestions'] = count($this->_productsModel->CountVisibleProductFaq($item['product_id']));
            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
}
