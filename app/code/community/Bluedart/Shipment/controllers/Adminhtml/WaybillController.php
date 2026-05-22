<?php
class Bluedart_Shipment_Adminhtml_WaybillController extends Mage_Adminhtml_Controller_Action
{
      public function indexAction() {
         $this->loadLayout();
         $this->_title($this->__("Blue Dart"));
         $this->renderLayout();
      }

      public function GeneratewaybillAction(){
		    $ispickupcreate = $this->getRequest()->getParam('bluedart_is_pickup_require');  		
        $shipper_area = $this->getRequest()->getParam('shipper_area');
        $shipper_customercode = $this->getRequest()->getParam('shipper_customercode');
        
        $chktopay = $this->getRequest()->getParam('chktopay');
        
        if($chktopay == 'chktopay') {
          $chktopay_fin = true;
        } else {
          $chktopay_fin = false;
        }

        $shipper_clientname = $this->getRequest()->getParam('shipper_clientname');
        $shipper_address1 = $this->getRequest()->getParam('shipper_address1');
        $shipper_address2 = $this->getRequest()->getParam('shipper_address2');
        $shipper_address3 = $this->getRequest()->getParam('shipper_address3');
        $shipper_pincode = $this->getRequest()->getParam('shipper_pincode');
        $shipper_mobile = $this->getRequest()->getParam('shipper_mobile');
        $shipper_telephone = $this->getRequest()->getParam('shipper_telephone');
        $shipper_sender = $this->getRequest()->getParam('shipper_sender');
        $shipper_sender = substr($shipper_sender,0,20);
        $shipper_email = $this->getRequest()->getParam('shipper_email');
        $shipper_vendorcode = $this->getRequest()->getParam('shipper_vendorcode');
        $consignee_name = $this->getRequest()->getParam('consignee_name');
        $consignee_adress1 = $this->getRequest()->getParam('consignee_adress1');
        $consignee_adress2 = $this->getRequest()->getParam('consignee_adress2');
        $consignee_adress3 = $this->getRequest()->getParam('consignee_adress3');
        $consignee_pincode = $this->getRequest()->getParam('consignee_pincode');
        $consignee_mobile = $this->getRequest()->getParam('consignee_mobile');
        $consignee_telephone = $this->getRequest()->getParam('consignee_telephone');
        $consignee_attention = $this->getRequest()->getParam('consignee_attention');
        $service_pieces = $this->getRequest()->getParam('service_pieces');
        $service_productcode = strtoupper($this->getRequest()->getParam('service_productcode'));
        $service_subproduct = strtoupper($this->getRequest()->getParam('service_subproduct'));
        $service_actweight = $this->getRequest()->getParam('service_actweight');
        $service_invoiceno = $this->getRequest()->getParam('service_invoiceno');
        $service_dox = $this->getRequest()->getParam('dox');
        
        $service_packtype = $this->getRequest()->getParam('service_packtype');
        $service_creaditrefno = $this->getRequest()->getParam('service_creaditrefno');
        $service_spclinstruction = $this->getRequest()->getParam('service_spclinstruction');
        $service_pickupdate = $this->getRequest()->getParam('service_pickupdate');
        $service_pickupreadytime = $this->getRequest()->getParam('service_pickupreadytime');
        $service_declaredval = $this->getRequest()->getParam('service_declaredval');
        $service_colamount = $this->getRequest()->getParam('service_colamount');
        $service_waybillnumber = $this->getRequest()->getParam('service_waybillnumber');
        $service_cmdtydetail1 = $this->getRequest()->getParam('service_cmdtydetail1');
        $service_cmdtydetail1 = substr($service_cmdtydetail1,0,30);        
        $service_cmdtydetail2 = $this->getRequest()->getParam('service_cmdtydetail2');
        $service_cmdtydetail2 = substr($service_cmdtydetail2,0,30);
        $service_cmdtydetail3 = $this->getRequest()->getParam('service_cmdtydetail3');
        $service_cmdtydetail3 = substr($service_cmdtydetail3,0,30);
        $service_dimentionsl = $this->getRequest()->getParam('service_dimentionsl');
        $service_dimentionsb = $this->getRequest()->getParam('service_dimentionsb');
        $service_dimentionsh = $this->getRequest()->getParam('service_dimentionsh');
        $service_count = $this->getRequest()->getParam('service_count');
        $PDFOutputNotRequired = "false";
        $PDFOutputNotRequired = $this->getRequest()->getParam('PDFOutputNotRequired');
       
        for($a=1;$a<=$this->getRequest()->getParam('counter_val');$a++)
        {
          $product_id = $this->getRequest()->getParam('bluedart_item_id_' . $a);
          $bluedartIitemsQty[$product_id] = $this->getRequest()->getParam('bluedart_total_items_' . $product_id);
        }
        

        $order = Mage::getModel('sales/order')->loadByIncrementId($this->getRequest()->getParam('bluedart_shipment_original_reference'));
       

         // Store Config Data
       $apitype = Mage::getStoreConfig('bluedart/settings/api_type');
       $area = Mage::getStoreConfig('bluedart/settings/area');
       $customercode = Mage::getStoreConfig('bluedart/settings/customercode');
       $licencekey = Mage::getStoreConfig('bluedart/settings/licencekey');
       $loginid = Mage::getStoreConfig('bluedart/settings/loginid');
       $password = Mage::getStoreConfig('bluedart/settings/password');
       $version = Mage::getStoreConfig('bluedart/settings/version');
       $previuosUrl = Mage::getSingleton('core/session')->getPreviousUrl();
       // Store Config Data

        $itemsv = $order->getAllVisibleItems();
        
        $prod_qty = array();

        foreach($itemsv as $item){
        	$prod_qty[$item->getId()] = $this->getRequest()->getParam('bluedart_input_items_qty_'.$item->getId());
        }

		
		$pickupurl = $this->getUrl("admin_shipment/adminhtml_pickupregistration/index",array('order_id'=>$order->getId()));
		$url = $this->getRequest()->getParam('bluedart_shipment_referer');	
			
        //error_log($url);
      
      
      $waybillgenerationurl = Mage::getStoreConfig('bluedart/url/waybillgeneration');
      if(!$waybillgenerationurl)
      {
		Mage::getSingleton('adminhtml/session')->addError("Please enter waybill generation URL from system configuration.");
		Mage::app()->getResponse()->setRedirect($url)->sendResponse();
		return;
	  }
	  
      $soap = new SoapClient($waybillgenerationurl.'?wsdl',
      array(
      'trace'               => 1,  
      'style'               => SOAP_DOCUMENT,
      'use'                 => SOAP_LITERAL,
      'soap_version'        => SOAP_1_1
      ));
      
      $soap->__setLocation($waybillgenerationurl."/Basic");
      
      $soap->sendRequest = true;
      $soap->printRequest = false;
      $soap->formatXML = true;  
   
        #echo "Start  of Soap 1.2 version (ws_http_Binding)  setting";
        $waybillgenerationurl = Mage::getStoreConfig('bluedart/url/waybillgeneration');
        $soap = new SoapClient($waybillgenerationurl.'?wsdl',
        array(
        'trace'               => 1,  
        'style'               => SOAP_DOCUMENT,
        'use'                 => SOAP_LITERAL,
        'soap_version'        => SOAP_1_2
        ));
        
        $soap->__setLocation($waybillgenerationurl);
        
        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true; 
        
        $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IWayBillGeneration/GenerateWayBill',true);
        $soap->__setSoapHeaders($actionHeader); 
    #echo "end of Soap 1.2 version (WSHttpBinding)  setting";


$params = array(
'Request' => 
  array (
    'Consignee' =>
      array (
        'ConsigneeAddress1' => $consignee_adress1,
        'ConsigneeAddress2' => $consignee_adress2,
        'ConsigneeAddress3'=> $consignee_adress3,
        'ConsigneeAttention'=> $consignee_attention,
        'ConsigneeMobile'=> $consignee_mobile,
        'ConsigneeName'=> $consignee_name,
        'ConsigneePincode'=> $consignee_pincode,
        'ConsigneeTelephone'=> $consignee_telephone,
      ) ,
    'Services' => 
      array (
        'ActualWeight' => $service_actweight,
        'CollectableAmount' => $service_colamount,
        'Commodity' =>
          array (
            'CommodityDetail1'  => $service_cmdtydetail1,
            'CommodityDetail2' => $service_cmdtydetail2,
            'CommodityDetail3' => $service_cmdtydetail3
        ),
        'CreditReferenceNo' => $service_creaditrefno,
        'DeclaredValue' => $service_declaredval,
        'Dimensions' =>
          array (
            'Dimension' =>
              array (
                'Breadth' => $service_dimentionsb,
                'Count' => $service_count,
                'Height' => $service_dimentionsh,
                'Length' => $service_dimentionsl
              ),
          ),
          'InvoiceNo' => $service_invoiceno,
          'PackType' => $service_packtype,
          'PickupDate' => $service_pickupdate,
          'PickupTime' => $service_pickupreadytime,
          'PieceCount' => $service_pieces,
          'ProductCode' => $service_productcode,
          'ProductType' => $service_dox,
          'SpecialInstruction' => $service_spclinstruction,
          'SubProductCode' => $service_subproduct,
          'ServiceWayBillNumber' => $service_waybillnumber
      ),
      'Shipper' =>
        array(
          'CustomerAddress1' => $shipper_address1,
          'CustomerAddress2' => $shipper_address2,
          'CustomerAddress3' => $shipper_address3,
          'CustomerCode' => $shipper_customercode,
          'CustomerEmailID' => $shipper_email,
          'CustomerMobile' => $shipper_mobile,
          'CustomerName' => $shipper_clientname ,
          'CustomerPincode' => $shipper_pincode,
          'CustomerTelephone' => $shipper_telephone,
          'IsToPayCustomer' =>  $chktopay_fin,
          'OriginArea' => $shipper_area,
          'Sender' => $shipper_sender,
          'VendorCode' => $shipper_vendorcode
        )
  ),
  'Profile' => 
     array(
      'Api_type' => $apitype,
      'LicenceKey'=>$licencekey,
      'LoginID'=>$loginid,
      'Version'=>$version)
      );
  
   

// Here I call my external function
try{


$_SESSION['GenerateWayBillSession'] = $params['Request'];
$_SESSION['GenerateWayForpickup'] = $params['Request'];
$_SESSION['bluedartItemsQty'] = $bluedartIitemsQty;

$result = $soap->__soapCall('GenerateWayBill',array($params));
/*
echo "<pre>";
print_r($result);
exit;
*/
$awbno = $result->GenerateWayBillResult->AWBNo;

if($awbno){

$DestinationArea = $result->GenerateWayBillResult->DestinationArea;
$DestinationLocation = $result->GenerateWayBillResult->DestinationLocation;
$CCRCRDREF = $result->GenerateWayBillResult->CCRCRDREF;

$extra_details = $DestinationArea.":".$DestinationLocation.":".$CCRCRDREF;

	$awb_pdf = "";
  
  if($PDFOutputNotRequired == "false") {
    
    if($result->GenerateWayBillResult->AWBPrintContent) {
      
      $awb_pdf =  $result->GenerateWayBillResult->AWBPrintContent;
    
    }
  
  }

  unset($_SESSION['GenerateWayBillSession']);
  unset($_SESSION['bluedartItemsQty']);

    if($order->canShip()) {  
              
      $shipmentid = Mage::getModel('sales/order_shipment_api')->create($order->getIncrementId(), $this->getRequest()->getParam('bluedart_items'));              

      $shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentid);          

      $track = Mage::getModel('sales/order_shipment_track')->setCarrierCode('bluedart')
                                  ->setTitle('Blue Dart')
                                  ->setDescription($extra_details)
                                  ->setNumber($awbno);
        
      $shipment->addTrack($track)->save();     

      if($awb_pdf != "") {
        $this->generatepdf($awb_pdf,$awbno);

        	$awfile = "var/bluedart/".$awbno.".pdf";
        	if (file_exists($awfile)) {
      			Mage::getSingleton('core/session')->addSuccess('AWB Pdf generated succesfully.');
			} else {
    			Mage::getSingleton('adminhtml/session')->addError("AWB Pdf not generated. Please check the permission for var/bluedart/ folder.");
			}

      }

      Mage::getSingleton('core/session')->addSuccess('Blue Dart Shipment Number: '.$shipmentid.' With AWB Number: '.$awbno.' has been created.');
      //error_log('before redirect--->'.$url);
      if($pickupurl && $ispickupcreate)
      {
		  Mage::app()->getResponse()->setRedirect($pickupurl)->sendResponse();
	  }
	  else
	  {
		  Mage::app()->getResponse()->setRedirect($url)->sendResponse();
	  }
      
     // $this->_redirectUrl('http://basic.bluedart.com/');

      /* $order->setState('warehouse_pickup_shipped', true); */
    } else{
      Mage::throwException($this->__('Cannot do shipment for the order.'));         
    }

}
else{
$error = '';
//echo  $result->GenerateWayBillResult->Status->WayBillGenerationStatus[0]->StatusCode;
$count = count($result->GenerateWayBillResult->Status->WayBillGenerationStatus);
if($count>1){
for($i=0;$i<$count;$i++){
$error = $error."<br>".$result->GenerateWayBillResult->Status->WayBillGenerationStatus[$i]->StatusInformation;
}
}else{
  $error = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;
}

  //echo $error;
  Mage::throwException($this->__($error));
  Mage::app()->getResponse()->setRedirect($url)->sendResponse();
  exit;

}


  }catch(Exception $e){
    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
    Mage::app()->getResponse()->setRedirect($url)->sendResponse();
  } 


  exit;
}

    public function generatepdf($awb_pdf,$awbno){
      	
      	$file = fopen("var/bluedart/".$awbno.".pdf","w+");
      	fwrite($file,$awb_pdf);
      	fclose($file);

      	return;
    }

}
