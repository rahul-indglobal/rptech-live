<?php
namespace Brainvire\MissingOrder\Controller\Payment;

class Revertqty extends \Magento\Framework\App\Action\Action {
	protected $missingOrderHelper;
	protected $quote = false;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Brainvire\MissingOrder\Helper\Data $missingOrderHelper,
		\Magento\Checkout\Model\Session $checkoutSession
	) {
		$this->missingOrderHelper = $missingOrderHelper;
		$this->checkoutSession = $checkoutSession;
		parent::__construct($context);
	}

	public function execute() {

		if ($this->missingOrderHelper->isModuleEnabled() == 1) {
			$itemsVisible = $this->getQuote()->getAllVisibleItems();
			foreach ($itemsVisible as $item) {
				$this->missingOrderHelper->updateQty($item->getProductId(), $item->getQty(), "plus");
			}
		}
	}
	protected function getQuote() {
		// if (!$this->quote) {
		$this->quote = $this->checkoutSession->getQuote();
		// }
		return $this->quote;
	}
}
