<?php
namespace I98commerce\Prodfaqs\Controller\Index;

use I98commerce\Prodfaqs\Helper\Data;
use I98commerce\Prodfaqs\Model\AnswersFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use I98commerce\Prodfaqs\Model\Faqs as FaqsModel;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Module\Dir\Reader;
use Magento\Store\Model\StoreManagerInterface;

class Answerpost extends \Magento\Framework\App\Action\Action
{

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var AnswersFactory
     */
    protected $answersModel;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var string
     */
    private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";

    /**
     * @var string
     */
    private static $_version = "php_1.0";

        const XML_PATH_EMAIL_RECIPIENT = 'prodfaqs/email/recipient';
        const XML_PATH_EMAIL_SENDER = 'prodfaqs/email/sender';
        const XML_PATH_EMAIL_TEMPLATE = 'prodfaqs/email/answertemplate';

        const XML_PATH_EMAIL_REPLY_SUBJECT = 'prodfaqs/email_reply/subject';
        const XML_PATH_EMAIL_REPLY_MESSAGE = 'prodfaqs/email_reply/body';

        const CONFIG_CAPTCHA_ENABLE = 'prodfaqs/google_options/captchastatus';
        const CONFIG_CAPTCHA_PRIVATE_KEY = 'prodfaqs/google_options/googleprivatekey';

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleReader;
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * Desc
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param AnswersFactory $answersModel
     * @param ScopeConfigInterface $scopeConfig
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param Data $myModuleHelper
     * @param Reader $moduleReader
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \I98commerce\Prodfaqs\Model\AnswersFactory $answersModel,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \I98commerce\Prodfaqs\Helper\Data $myModuleHelper,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
    ) {

        $this->jsonFactory = $jsonFactory;
        $this->answersModel = $answersModel;
        $this->scopeConfig = $scopeConfig;
        $this->_mymoduleHelper = $myModuleHelper;
        $this->_transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->moduleReader = $moduleReader;
        $this->_remoteAddress = $remoteAddress;
        parent::__construct($context);
    }

    /**
     * Desc
     *
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();

        $params =  $this->getRequest()->getPostValue();

         $remoteAddress = new \Magento\Framework\Http\PhpEnvironment\RemoteAddress($this->getRequest());
         $visitorIp = $remoteAddress->getRemoteAddress();

        $error = false;
        $message = '';

        $captcha_enable = $this->scopeConfig->getValue(self::CONFIG_CAPTCHA_ENABLE);

        if ($captcha_enable) {
             $captcha =   $params["g-recaptcha-response"];
            $secret =  $this->scopeConfig->getValue(self::CONFIG_CAPTCHA_PRIVATE_KEY);

            $response = null;
            $path = self::$_siteVerifyUrl;
            $dataC =  [
            'secret' => $secret,
            'remoteip' => $visitorIp,
            'v' => self::$_version,
            'response' => $captcha
            ];
            $req = "";
            foreach ($dataC as $key => $value) {
                 $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
            }
        // Cut the last '&'
            $req = substr($req, 0, strlen($req)-1);
            $response = file_get_contents($path . $req);
            $answers = json_decode($response, true);
            if (trim($answers ['success']) == true) {
                $error = false;
            } else {
                // Dispay Captcha Error

                $error = true;
                $message = "Incorrect Captcha Key";
            }
        }
        if (!strcasecmp((string)$params['customer_name'], 'admin')) {
            $error = true;
            $message = "Please use another name";
        }
        if ($error == false) {
            $faq_id = (int) $params['faq_id'];
            $answer = $params['answer'];
            $customer_name = $params['customer_name'];
            $customer_email = $params['customer_email'];
            $status = $params['status'];
            $custid = $params['custid'];
            $faq_question = ['faq_question'];

            try {
                //check if product-faqs identifier exist, otherwise add

                date_default_timezone_set($this->_mymoduleHelper->timezone());
                $rep_time = date("Y-m-d").' '.date("h:i:s");

                $newsArray['create_date']= $rep_time;

                $newData = ['answer' => $answer, 'status' => $status,  'answer_by'=> $customer_name, 'user_email'=> $customer_email, 'faq_id'=> $faq_id,'user_id'=>$custid,'create_date'=>$rep_time];
               // $model = $this->faqsModel;
               // $model->setData($newData);
               // $model->save();
                $ansmodel = $this->answersModel->create();
                    $ansmodel->setData($newData);
                    $ansmodel->save();
                $message = __('Answer posted successfully.');

                /*Email Sending Start*/
                $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($params);

                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $transport = $this->_transportBuilder
                                    ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
                                    ->setTemplateOptions(
                                        [
                                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                            'store' => $this->storeManager->getStore()->getId(),
                                        ]
                                    )
                                    ->setTemplateVars(['data' => $postObject])
                                    ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                                    ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                                    ->setReplyTo($params['customer_email'])
                                    ->getTransport();

                $transport->sendMessage();
                /*Email Sending End*/
            } catch (\Exception $ex) {
                $message = $ex->getMessage();
                $error = true;
            }
        }

        return  $resultJson->setData([
                'message' => $message,
                'error' => $error
            ]);
    }

    /**
     * Desc
     *
     * @param string $params
     * @param string $privatekey
     * @return mixed
     */
    private function _checkRecaptchaAnswer($params, $privatekey)
    {

        $resp = recaptcha_check_answer(
            $privatekey,
            $this->rmVisitorIp(),
            $params["recaptcha_challenge_field"],
            $params["recaptcha_response_field"]
        );
        return $resp;
    }

    /**
     * Desc
     *
     * @param string $moduleName
     * @param string $type
     * @return string
     */
    public function dfModuleDir($moduleName, $type = '')
    {
        return $this->moduleReader->getModuleDir($type, $moduleName);
    }

    /**
     * Desc
     *
     * @return false|string
     */
    public function rmVisitorIp()
    {
        return $this->_remoteAddress->getRemoteAddress();
    }
}
