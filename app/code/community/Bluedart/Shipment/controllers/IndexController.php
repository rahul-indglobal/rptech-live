<?php
class Bluedart_Shipment_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Titlename"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("titlename", array(
                "label" => $this->__("Titlename"),
                "title" => $this->__("Titlename")
		   ));

      $this->renderLayout(); 
	  
    }

        public function PincodevalidateAction(){
       $pincode = $this->getRequest()->getParam('pincode');
      try{
        $servicefinderurl = Mage::getStoreConfig('bluedart/url/servicefinder');
        $soap = new SoapClient($servicefinderurl.'?wsdl',
        array(
        'trace'               => 1,  
        'style'               => SOAP_DOCUMENT,
        'use'                 => SOAP_LITERAL,
        'soap_version'        => SOAP_1_2
        ));
        
        $soap->__setLocation($servicefinderurl);
        
        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true;
        
        
        $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true);
        $soap->__setSoapHeaders($actionHeader);
    
    #echo End of Soap1.2 (ws_Http_Version)

$params = array('pinCode' => $pincode,
     'profile' => 
     array(
      'Api_type' => 'T',
      'Area'=>'',
      'Customercode'=>'',
      'IsAdmin'=>'',
      'LicenceKey'=>'4ecbd06dc0b9737d69120029cb05c9df',
      'LoginID'=>'BOM00001',
      'Password'=>'',
      'Version'=>'1.3')
      );
      
#var_dump($params);
#echo '<h2>Parameters</h2><pre>'; print_r($params); echo '</pre>';
// Here I call my external function
try{
$result = $soap->__soapCall('GetServicesforPincode',array($params));
}
catch (Exception $e) {
         
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
#echo "<br>";
#var_dump($result);

echo $result->GetServicesforPincodeResult->ErrorMessage ;
}
catch (Exception $e) {
         
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

    }



        public function TransittimeAction(){
       $pincode = $this->getRequest()->getParam('pincode');
      try{
        $servicefinderurl = Mage::getStoreConfig('bluedart/url/servicefinder');
        $soap = new SoapClient($servicefinderurl.'?wsdl',
        array(
        'trace'               => 1,  
        'style'               => SOAP_DOCUMENT,
        'use'                 => SOAP_LITERAL,
        'soap_version'        => SOAP_1_2
        ));
        
        $soap->__setLocation($servicefinderurl);
        
        $soap->sendRequest = true;
        $soap->printRequest = false;
        $soap->formatXML = true;
        
        
        $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true);
        $soap->__setSoapHeaders($actionHeader);
    
    #echo End of Soap1.2 (ws_Http_Version)

$params = array('pPinCodeFrom' => '400067',
  'pPinCodeTo' => '396195',
 // 'pProductCode' => ,
 // 'pSubProductCode' => $pincode,
  'pPudate' => '13-03-2015',
  'pPickupTime' => '11:15',
     'profile' => 
     array(
      'Api_type' => 'T',
      'Area'=>'',
      'Customercode'=>'',
      'IsAdmin'=>'',
      'LicenceKey'=>'4ecbd06dc0b9737d69120029cb05c9df',
      'LoginID'=>'BOM00001',
      'Password'=>'',
      'Version'=>'1.3')
      );
      
#var_dump($params);
#echo '<h2>Parameters</h2><pre>'; print_r($params); echo '</pre>';
// Here I call my external function
try{
$result = $soap->__soapCall('GetDomesticTransitTimeForPinCodeandProduct',array($params));
}
catch (Exception $e) {
         
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
#echo "<br>";
var_dump($result);

//echo $result->GetServicesforPincodeResult->ErrorMessage ;
}
catch (Exception $e) {
         
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

    }
}
