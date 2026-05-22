<?php
class Bluedart_Shipment_Adminhtml_ShipmentimportController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Blue Dart"));
	   $this->renderLayout();
    }

    public function PincodevalidateAction(){
       $pincode = $this->getRequest()->getParam('pincode');
       $productcode = $this->getRequest()->getParam('productcode');
       $subproduct = $this->getRequest()->getParam('subproduct');
       $apitype = Mage::getStoreConfig('bluedart/settings/api_type');
       $area =   Mage::getStoreConfig('bluedart/settings/area');
       $customercode =   Mage::getStoreConfig('bluedart/settings/customercode');
       $licencekey =   Mage::getStoreConfig('bluedart/settings/licencekey');
       $loginid =   Mage::getStoreConfig('bluedart/settings/loginid');
       $password =   Mage::getStoreConfig('bluedart/settings/password');
       $version =   Mage::getStoreConfig('bluedart/settings/version');
       $previuosUrl=Mage::getSingleton('core/session')->getPreviousUrl();

        $servicefinderurl = Mage::getStoreConfig('bluedart/url/servicefinder');
		 if(!$servicefinderurl)
		  {
			echo "Please enter Service Finder url from system configuration.";
			exit;
		  }
	  
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
	'pProductCode' => $productcode,
	'pSubProductCode' => $subproduct,
     'profile' => 
     array(
      'Api_type' => $apitype,
      'Area'=>$area,
      'Customercode'=>$customercode,
      'IsAdmin'=>'',
      'LicenceKey'=>$licencekey,
      'LoginID'=>$loginid,
      'Password'=>$password,
      'Version'=>$version)
      );
      
#var_dump($params);
#echo '<h2>Parameters</h2><pre>'; print_r($params); echo '</pre>';
// Here I call my external function
try{
$result = $soap->__soapCall('GetServicesforPincode',array($params));
echo "
<div class='divTable'>
             <div class='headRow'>
                <div class='divCell pincodes' align='center'>
                  <span>Pincode:</span><span>" . $result->GetServicesforPincodeResult->PinCode . "</span>
                  <span>Pincode Desciption:</span><span>".$result->GetServicesforPincodeResult->PincodeDescription."</span>
                  <span>Area Code:</span><span>".$result->GetServicesforPincodeResult->AreaCode."</span>
                  <span>Service Center Code:</span><span>".$result->GetServicesforPincodeResult->ServiceCenterCode."</span>
                  <span>Product Code:</span><span>" . $productcode . "</span>
                  <span>Sub Product Code:</span><span>C</span>
                  <span>Product Description:</span><span>eTailCODAir</span>
                  <span>eTailCODAirInbound:</span><span>".$result->GetServicesforPincodeResult->eTailCODAirInbound."</span>
                  <span>eTailCODAirOutbound:</span><span>".$result->GetServicesforPincodeResult->eTailCODAirOutbound."</span>
                  <span>eTailCODGroundInbound:</span><span>".$result->GetServicesforPincodeResult->eTailCODGroundInbound."</span>
                  <span>eTailCODGroundOutbound:</span><span>".$result->GetServicesforPincodeResult->eTailCODGroundOutbound."</span>
                  <span>eTailPrePaidAirInbound:</span><span>".$result->GetServicesforPincodeResult->eTailPrePaidAirInbound."</span>
                  <span>eTailPrePaidAirOutound:</span><span>".$result->GetServicesforPincodeResult->eTailPrePaidAirOutound."</span>
                  <span>eTailPrePaidGroundInbound:</span><span>".$result->GetServicesforPincodeResult->eTailPrePaidGroundInbound."</span>
                  <span>eTailPrePaidGroundOutbound:</span><span>".$result->GetServicesforPincodeResult->eTailPrePaidGroundOutbound."</span>
                </div>
              </div>
      </div>

";
}
catch (Exception $e) {

					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				//	$this->_redirectUrl($previuosUrl);
				}
//var_dump($result);
//print_r($result);
//echo $result->GetServicesforPincodeResult->ErrorMessage ;


    }

         public function TransittimeAction(){
       $pPinCodeFrom = $this->getRequest()->getParam('pPinCodeFrom');
       $pPinCodeTo = $this->getRequest()->getParam('pPinCodeTo');
       $pProductCode = $this->getRequest()->getParam('pProductCode');
       $pSubProductCode = $this->getRequest()->getParam('pSubProductCode');
       $pPudate = $this->getRequest()->getParam('pPudate');
       $pPickupTime = $this->getRequest()->getParam('pPickupTime');

      try{		  
        $servicefinderurl = Mage::getStoreConfig('bluedart/url/servicefinder');
        if(!$servicefinderurl)
		  {
			echo "Please enter Service Finder url from system configuration.";
			exit;
		  }
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
        
        
        $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetDomesticTransitTimeForPinCodeandProduct',true);
        $soap->__setSoapHeaders($actionHeader);
    
    #echo End of Soap1.2 (ws_Http_Version)
        //echo date('c');exit;
        $pudate = $pPudate.'T00:00:00+00:00';
$params = array('pPinCodeFrom' => $pPinCodeFrom,
  'pPinCodeTo' => $pPinCodeTo,
  'pProductCode' => $pProductCode,
  'pSubProductCode' => $pSubProductCode,
  'pPudate' => $pudate,
  'pPickupTime' => $pPickupTime,
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
 // print_r($params);exit;
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
