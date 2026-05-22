<?php
class Bluedart_Shipment_Adminhtml_ShipmenttransitController extends Mage_Adminhtml_Controller_Action
{
    	public function indexAction()
        {
         $this->loadLayout();
    	   $this->_title($this->__("Blue Dart"));
    	   $this->renderLayout();
        }
      
      public function TransittimeAction (){
       $pPinCodeFrom = $this->getRequest()->getParam('pPinCodeFrom');
       $pPinCodeTo = $this->getRequest()->getParam('pPinCodeTo');
       $pProductCode = strtoupper($this->getRequest()->getParam('pProductCode'));
       $pSubProductCode = strtoupper($this->getRequest()->getParam('pSubProductCode'));
       $pPudate = $this->getRequest()->getParam('pPudate');
       $pPickupTime = $this->getRequest()->getParam('pPickupTime');

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

        $pudate = $pPudate.'T00:00:00+00:00';
        $params = array('pPinCodeFrom' => $pPinCodeFrom,
                        'pPinCodeTo' => $pPinCodeTo,
                        'pProductCode' => $pProductCode,
                        'pSubProductCode' => $pSubProductCode,
                        'pPudate' => $pudate,
                        'pPickupTime' => $pPickupTime,
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

            try{
                 $result = $soap->__soapCall('GetDomesticTransitTimeForPinCodeandProduct',array($params));
            }
            catch (Exception $e) {
                  Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }

            //echo "<pre>";
            //print_r($result);   

  if($result->GetDomesticTransitTimeForPinCodeandProductResult->ErrorMessage == 'Valid') {
      echo "
        <div class='divTable'>
        <div class='headRow'>
        <div class='divCell pincodes' align='center'>
        <p><span><b>Transit Time Details </b></span></p>
        <p><span><b>Area </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->Area."</span></p>
        <p><span><b>City Destination </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->CityDesc_Destination."</span></p>
        <p><span><b>City Origin </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->CityDesc_Origin."</span></p>
        <p><span><b>EDL Message </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->EDLMessage."</span></p>
        
        <p><span><b>Expected Delivery Date </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->ExpectedDateDelivery."</span></p>
        <p><span><b>Expected POD Date </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->ExpectedDatePOD."</span></p>
        <p><span><b>Service Center </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->ServiceCenter."</span></p>
        <p><span><b>Additional Days </b></span><span>".$result->GetDomesticTransitTimeForPinCodeandProductResult->AdditionalDays."</span></p>
        
        </div>
        </div>
      </div>";

  } else {

      if($result->GetDomesticTransitTimeForPinCodeandProductResult->ErrorMessage != "") {
        $res_error = $result->GetDomesticTransitTimeForPinCodeandProductResult->ErrorMessage;
      } else {
        $res_error = "Invalid Input"; 
      } 

      echo "
        <div class='divTable'>
        <div class='headRow'>
        <div class='divCell pincodes' align='center'>
        <p><span><b>Transit Time Details </b></span></p>
        <p><span><b>IsError </b></span><span class='error_msg'>True</span></p>
        <p><span><b>Description </b></span><span class='error_msg'>".$res_error."</span></p>
        </div>
        </div>
        </div>";
  }

}
catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

    }
}
