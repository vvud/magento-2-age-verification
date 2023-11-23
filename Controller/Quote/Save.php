<?php
/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magentiz\AgeVerification\Controller\Quote;

class Save implements \Magento\Framework\App\Action\HttpPostActionInterface
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
            $dob = $this->request->getParam('dob');
            if ($dob) {
                // Check date
                if (!$this->isValidDate($dob)) {
                    $result = [
                        'error' => __('Bitte geben Sie ein gültiges Datum an (dd.mm.yyyy).')
                        // 'error' => __('Please enter a valid date (dd.mm.yyyy).')
                    ];
                    throw new \Exception(__('Bitte geben Sie ein gültiges Datum an (dd.mm.yyyy).'));
                }

                $dobFormat = new \DateTime($dob);
                $currentDate = new \DateTime();
                // Calculate the number of gaps between dob and today
                $timeDifference = $currentDate->diff($dobFormat);
                // Convert to year
                $yearsDifference = $timeDifference->y;
                if ($yearsDifference < 18) {
                    $result = [
                        'error' => __('Hoppla! Offenbar sind Sie nicht alt genug, um unsere Produkte zu kaufen. Bitte überprüfen Sie noch einmal Ihr Alter.')
                        // 'error' => __('Oops! It seems you\'re not old enough to buy our products. Please check your age again.')
                    ];
                    throw new \Exception(__('Hoppla! Offenbar sind Sie nicht alt genug, um unsere Produkte zu kaufen. Bitte überprüfen Sie noch einmal Ihr Alter.'));
                }

                $quote = $this->checkoutSession->getQuote();
                if (!$quote->getItemsCount()) {
                    $result = [
                        'error' => __('Der Warenkorb enthält keine Produkte.')
                        // 'error' => __('Cart doesn\'t contain products.')
                    ];
                    throw new \Exception(__('Der Warenkorb enthält keine Produkte.'));
                }
                $quote->setData('dob', $dob);
                $this->quoteRepository->save($quote);
                $result['success'] = true;
            } else {
                $result = [
                    'error' => __('Ungültiges Geburtsdatum.')
                    // 'error' => __('Not valid Date of Birth.')
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
     * @param $dateString
     * @param string $format
     * @return bool
     */
    private function isValidDate($dateString, $format = 'd.m.Y') {
        $dateTime = \DateTime::createFromFormat($format, $dateString);

        return $dateTime && $dateTime->format($format) === $dateString;
    }
}
