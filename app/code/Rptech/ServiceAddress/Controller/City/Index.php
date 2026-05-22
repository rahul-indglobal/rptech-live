<?php

namespace Rptech\ServiceAddress\Controller\City;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Rptech\ServiceAddress\Model\AddressFactory;

/**
 * Class Index
 * @package Rptech\ServiceAddress\Controller\City
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var AddressFactory
     */
    protected $addressFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param AddressFactory $addressFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        AddressFactory $addressFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->addressFactory = $addressFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $cityId = $this->getRequest()->getParam("city_id");
        $response = [
            "success" => false,
            "message" => ""
        ];
        try {
            if (!empty($cityId)) {
                /**
                 * @var \Rptech\ServiceAddress\Model\Address $address
                 */
                $address = $this->addressFactory->create()->load($cityId);
                if ($address->getId()) {
                    $response["data"] = $address->getData();
                    $response["success"] = true;
                }
            }
        } catch (\Exception $ex) {
            $response["message"] = __($ex->getMessage());
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}