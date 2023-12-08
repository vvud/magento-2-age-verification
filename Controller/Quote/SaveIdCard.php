<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Controller\Quote;

class SaveIdCard implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->request = $request;
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        try {
            $type = $this->request->getParam('av_type');
            $number = $this->request->getParam('av_number');
            if ($type && $number) {
                $quote = $this->checkoutSession->getQuote();
                if (!$quote->getItemsCount()) {
                    $result = [
                        // 'error' => __('Der Warenkorb enthält keine Produkte.')
                        'error' => __('Cart doesn\'t contain products.')
                    ];
                    throw new \Exception(__('Cart doesn\'t contain products.'));
                }
                $quote->setData('av_type', $type);
                $quote->setData('av_number', $number);
                $this->quoteRepository->save($quote);
                $result['success'] = true;
            } else {
                $result = [
                    'error' => __('Not valid ID Number.')
                ];
            }

        } catch (\Exception $e) {
            $result = [
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ];
        }

        return $resultJson->setData($result);;
    }
}
