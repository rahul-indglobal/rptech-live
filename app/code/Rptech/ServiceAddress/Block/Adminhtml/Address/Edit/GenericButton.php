<?php
/**
 * @author Rptech
 * @package Rptech_ServiceAddress
 */
namespace Rptech\ServiceAddress\Block\Adminhtml\Address\Edit;

use Magento\Backend\Block\Widget\Context;
use Rptech\ServiceAddress\Model\AddressFactory;

/**
 * Class GenericButton
 * @package Rptech\ServiceAddress\Block\Adminhtml\Address\Edit
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var AddressFactory
     */
    protected $addressFactory;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param AddressFactory $brandFactory
     */
    public function __construct(
        Context $context,
        AddressFactory $addressFactory
    ) {
        $this->context = $context;
        $this->addressFactory = $addressFactory;
    }

    /**
     * Return CMS block ID
     *
     * @return int|null
     */
    public function getAddressId()
    {
        try {
            return $this->addressFactory->create()->load(
                $this->context->getRequest()->getParam('entity_id')
            )->getId();
        } catch (\Exception $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
