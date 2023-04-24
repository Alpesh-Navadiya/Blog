<?php
namespace I98commerce\Prodfaqs\Controller\Index;

use I98commerce\Prodfaqs\Model\Answers;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Model\Session as customerSession;
use Magento\Framework\Controller\ResultInterface;

class Likes extends \Magento\Framework\App\Action\Action
{

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var Answers
     */
    protected $_answersModel;

    /**
     * @var customerSession
     */
    protected $_customerSession;

    /**
     * Desc
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Answers $answersModel
     * @param customerSession $customerSession
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        \I98commerce\Prodfaqs\Model\Answers $answersModel,
        customerSession $customerSession
    ) {

        $this->jsonFactory = $jsonFactory;
        $this->_answersModel = $answersModel;
        $this->_customerSession = $customerSession;

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
        $params = $this->getRequest()->getParams();
        $error = false;
        $message = '';

        if (isset($params['faq'])) {
            $ans_id = (int) $params['faq'];
            $like_value = (int) $params['value'];
            $dislike_value = (int) $params['value2'];
            $sum_values = $like_value + $dislike_value;

            $answers = $this->_answersModel->load($ans_id);

            $likes_num = (int) $answers->getLikes();
            $dislikes_num = (int) $answers->getDislikes();
            $sum_nums = $likes_num + $dislikes_num;
            $new_likes = $likes_num + 1;
            try {
                $newData = ['likes' => $like_value , 'dislikes' => $dislike_value ];
                $answers->setData(array_merge($answers->getData(), $newData));

                $this->_answersModel->save($answers);
                $message = __('Thanks for your feedback.');

                //set in session to restrict customer over-rating for this faq

                $faqRating = $this->_customerSession->getLikesRating();
                $ar = explode(',', $faqRating);
                array_push($ar, $ans_id);

                $ar_str = implode(',', $ar);

                $customerSession->setLikesRating($ar_str);
            } catch (\Exception $ex) {
                $message = $ex->getMessage();
                $error = true;
            }
        }
        return  $resultJson->setData(['message' => $message, 'error' => $error ]);
    }
}
