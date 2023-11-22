<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Controller\Quote;

class Save implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    protected $quoteIdMaskFactory;

    protected $quoteRepository;

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
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        try {
            $dob = $this->request->getParam('dob');
            if ($dob) {
                // Check date
                if (!$this->isValidDate($dob)) {
                    $result = [
                        'error' => __('Please enter a valid date (dd.mm.yyyy).')
                    ];
                    throw new \Exception(__('Please enter a valid date (dd.mm.yyyy).'));
                }

                $dobFormat = new \DateTime($dob);
                $currentDate = new \DateTime();
                // Calculate the number of gaps between dob and today
                $timeDifference = $currentDate->diff($dobFormat);
                // Convert to year
                $yearsDifference = $timeDifference->y;
                if ($yearsDifference < 18) {
                    $result = [
                        'error' => __('Oops! It seems you\'re not old enough to buy our products. Please check your age again.')
                    ];
                    throw new \Exception(__('Oops! It seems you\'re not old enough to buy our products. Please check your age again.'));
                }

                $quote = $this->checkoutSession->getQuote();
                if (!$quote->getItemsCount()) {
                    $result = [
                        'error' => __('Cart doesn\'t contain products.')
                    ];
                    throw new \Exception(__('Cart doesn\'t contain products.'));
                }
                $quote->setData('dob', $dob);
                $this->quoteRepository->save($quote);
                $result['success'] = true;
            } else {
                $result = [
                    'error' => __('Not valid Date of Birth.')
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

    /**
     * Check if date is valid or not
     * @return bool
     */
    private function isValidDate($dateString, $format = 'd.m.Y') {
        $dateTime = \DateTime::createFromFormat($format, $dateString);
    
        return $dateTime && $dateTime->format($format) === $dateString;
    }
}
