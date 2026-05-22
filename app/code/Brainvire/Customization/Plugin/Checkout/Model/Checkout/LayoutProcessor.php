<?php
namespace Brainvire\Customization\Plugin\Checkout\Model\Checkout;

class LayoutProcessor
{
    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        $obj = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSess = $obj->get('Magento\Customer\Model\Session');
        if ($customerSess->isLoggedIn()) {
            $customerData = $customerSess->getCustomer()->getData();
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['firstname']['value'] = $customerData['firstname'];
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['lastname']['value'] = $customerData['lastname'];
            if(isset($customerData['mobilenumber'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
                ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['value'] = $customerData['mobilenumber'];
            }
            return $jsLayout;
        }
        return $jsLayout;
    }
}
