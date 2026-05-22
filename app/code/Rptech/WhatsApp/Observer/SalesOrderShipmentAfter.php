<?php

namespace Rptech\WhatsApp\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesOrderShipmentAfter implements ObserverInterface
{
    protected $addressRepository;
    protected $customerRepository;
    protected $helper;
    protected $logger;
    protected $session;
    protected $storeManager;
    protected $rptechHelper;
    protected $pdfInvoiceModel;
    protected $outputDirectory;
    protected $pdfInvoiceDirectory = "pdf_invoices";
    
    public function __construct(
            \Psr\Log\LoggerInterface $logger,
            \Magento\Customer\Model\Session $session,
            \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            \Rptech\Communication\Helper\Data $rptechHelper,
            \Magento\Sales\Model\Order\Pdf\Invoice $pdfInvoiceModel,
            \Magento\Framework\Filesystem $filesystem
    ) {
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
        $this->session = $session;
        $this->storeManager = $storeManager;
        $this->rptechHelper = $rptechHelper;
        $this->pdfInvoiceModel = $pdfInvoiceModel;
        $this->outputDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->info("The observer for WhatsApp message (shipment) started");
        $shipment = $observer->getEvent()->getShipment();
        /** @var \Magento\Sales\Model\Order $order */
        $order = $shipment->getOrder();
        try{
            if (!$order->hasInvoices()){
                $this->logger->info("No invoice found for order ".$order->getIncrementId());
               return $this;
            }
            $invoice =  $order->getInvoiceCollection()->getFirstItem();
            $pdfContent = $this->pdfInvoiceModel->getPdf([$invoice])->render();
            $pdf_file_name = $invoice->getIncrementId() . ".pdf";
            $this->outputDirectory->writeFile($this->pdfInvoiceDirectory. "/" . $pdf_file_name ,$pdfContent);
        } catch (Exception $e){
            $this->logger->info("Some error while saving PDF of invice from WhatsApp module");
            $this->logger->info($e->getMessage());
        }
        //$this->logger->info("Rptech WA observer started");
		if ($order->getCustomerId()) {
			$cid = $order->getCustomerId();
            $number = '';
			$customer = $this->customerRepository->getById($cid);
            $customerAttributeData = $customer->__toArray();
			if (isset($customerAttributeData['custom_attributes']['mobilenumber']['value'])) {
                $number = $customerAttributeData['custom_attributes']['mobilenumber']['value'];
            } else if ($customer->getDefaultShipping()) {
				$sid = $customer->getDefaultShipping();
				$ship_addr = $this->addressRepository->getById($sid);
				$number = $ship_addr->getTelephone();
			} elseif ($customer->getDefaultBilling()) {
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

                $orderId = $order->getIncrementId();
                $name = isset($customerAttributeData['firstname']) ? $customerAttributeData['firstname'] : '';
                $name .= isset($customerAttributeData['lastname']) ? ' '.$customerAttributeData['lastname'] : '';
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $storeUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
                $link = $storeUrl."sales/guest/form/";
                $doc = $mediaUrl."media/dummy.pdf";
                if (isset($pdf_file_name)) {
                    $doc = $mediaUrl.$this->pdfInvoiceDirectory."/".$pdf_file_name;
                }
                
                $resp = $this->rptechHelper->sendShipmentWhatsAppMessageToCustomer($number, $name, $orderId, $doc, $link);
                if($resp) {
                    $resp = json_decode($resp);
                    if (!is_object($resp) || $resp->status!="processing") {
                        $this->logger->info("Some error sending WhatsApp message of Send Enquiry");
                        $this->logger->info(gettype($resp));
                        $this->logger->info(print_r($resp,1));
                    }
                } else {
                    $this->logger->info("Shipment message on WhatsApp did not send.");
                }
            }
        }
        $this->logger->info("The observer for WhatsApp message (shipment) finished");
    }
}