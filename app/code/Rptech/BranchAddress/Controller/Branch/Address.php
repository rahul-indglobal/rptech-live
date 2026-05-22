<?php

namespace Rptech\BranchAddress\Controller\Branch;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Rptech\BranchAddress\Api\BranchAddressRepositoryInterface;

/**
 * Class Address
 * @package Rptech\BranchAddress\Controller\Branch
 */
class Address extends \Magento\Framework\App\Action\Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var BranchAddressRepositoryInterface
     */
    protected $branchAddressRepositoryInterface;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param BranchAddressRepositoryInterface $branchAddressRepositoryInterface
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        BranchAddressRepositoryInterface $branchAddressRepositoryInterface
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->branchAddressRepositoryInterface = $branchAddressRepositoryInterface;
        parent::__construct($context);
    }

    public function execute()
    {
        $branchId = $this->getRequest()->getParam("branch_id");
        $response = [
            "success" => false,
            "message" => ""
        ];
        try {
            if (!empty($branchId)) {
                $address = $this->branchAddressRepositoryInterface->getById($branchId);
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