<?php

namespace Rptech\WhatsApp\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Invoice as Invoice1;

class Invoice implements ObserverInterface
{
    protected $logger;
    protected $customerRepository;
    protected $addressRepository;
    protected $storeManager;
    protected $pdfInvoiceModel;
    protected $outputDirectory;
    protected $pdfInvoiceDirectory = "pdf_invoices";

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magecomp\Mobilelogin\Helper\Apicall $mobileHelper,
        \Rptech\General\Helper\Data $rptechHelper,
        \Magento\Sales\Model\Order\Pdf\Invoice $pdfInvoiceModel,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->logger = $logger;
        $this->mobileHelper = $mobileHelper;
        $this->rptechHelper = $rptechHelper;
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->pdfInvoiceModel = $pdfInvoiceModel;
        $this->outputDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        /** @var InvoiceInterface $invoice */
        $invoice = $observer->getEvent()->getInvoice();

        if (!$invoice) {
            return $this;
        }

        if ($invoice->getState() == Invoice1::STATE_PAID && $invoice->dataHasChangedFor('state')) {
            //$this->logger->info("Saving invoice first time");
            $pdfContent = $this->pdfInvoiceModel->getPdf([$invoice])->render();
            $pdf_file_name = $invoice->getIncrementId() . ".pdf";
            $this->outputDirectory->writeFile($this->pdfInvoiceDirectory. "/" . $pdf_file_name ,$pdfContent);

            $invoiceIncrementId = $invoice->getIncrementId();
            $order = $invoice->getOrder();
            $orderIncrementId = $order->getIncrementId();
            $customerId = $order->getCustomerId();

            $number = '';
            $customer = $this->customerRepository->getById($customerId);
            $customerAttributeData = $customer->__toArray();

            if(isset($customerAttributeData['custom_attributes']['mobilenumber']['value'])) {
                $number = $customerAttributeData['custom_attributes']['mobilenumber']['value'];
            } else if ($customer->getDefaultShipping()) {
                $sid = $customer->getDefaultShipping();
                $ship_addr = $this->addressRepository->getById($sid);
                $number = $ship_addr->getTelephone();
            } else if ($customer->getDefaultBilling()) {
                $bid = $customer->getDefaultBilling();
                $bill_addr = $this->addressRepository->getById($bid);
                $number = $bill_addr->getTelephone();
            }

            if (!empty($number)) {
                if (strlen($number)==12 || strlen($number)>10) {
                    if (substr($number, 0, 2)=="91") {
                        $number = "+".$number;
                    }
                } else if (strlen($number)<=10) {
                    $number = "+91".$number;
                }

                $name = isset($customerAttributeData['firstname']) ? $customerAttributeData['firstname'] : '';
                $name .= isset($customerAttributeData['lastname']) ? ' '.$customerAttributeData['lastname'] : '';
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $link = $storeUrl.'sales/guest/form/';
                $doc = $mediaUrl.$this->pdfInvoiceDirectory."/".$pdf_file_name;

                //$this->logger->info("Rptech_SMS Invoice.PHP sending message to ".$number);
                //$this->logger->info("Rptech_SMS Invoice.PHP message sent");
            }
        } else {
            //$this->logger->info("Saving invoice second time");
        }
        return;
    }
}
