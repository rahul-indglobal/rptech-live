<?php
class Bluedart_Shipment_Adminhtml_WaybillcancelationController extends Mage_Adminhtml_Controller_Action
{
    	public function indexAction()
        {
         $this->loadLayout();
    	   $this->_title($this->__("Blue Dart"));
    	   $this->renderLayout();
        }
      public function WaybillcancelAction(){
        
        $waybillnumber = $this->getRequest()->getParam('waybillnumber');
        
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
        $waybillgenerationurl = Mage::getStoreConfig('bluedart/url/waybillgeneration');
		if(!$waybillgenerationurl)
		{
			echo "Please enter Way Bill Generation URL from system configuration.";
			exit;
		}
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
        
        
        $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IWayBillGeneration/CancelWaybill',true);
        $soap->__setSoapHeaders($actionHeader);

        $params = array('Request'=>
              array('AWBNo' =>$waybillnumber),
                        'Profile' => 
                         array(
                          'Api_type' => $apitype,                          
                          'LicenceKey'=>$licencekey,
                          'LoginID'=>$loginid,
                          'Version'=>$version)
                          );

            try{
                 $result = $soap->__soapCall('CancelWaybill',array($params));
                 
                 if($result->CancelWaybillResult->Status->WayBillGenerationStatus->StatusCode == 'CancelFailure') {

                  $res_error = $result->CancelWaybillResult->Status->WayBillGenerationStatus->StatusInformation; 
                  
                  echo "
                  <div class='divTable'>
                  <div class='headRow'>
                  <div class='divCell pincodes' align='center'>
                  <p><span><b>WayBill Cancellation </b></span></p>
                  <p><span><b>IsError :</b></span><span class='error_msg'>True</span></p>
                  <p><span><b>Description :</b></span><span class='error_msg'>".$res_error."</span></p>
                  </div>
                  </div>
                  </div>";

                 } else {

                  $res_status = $result->CancelWaybillResult->Status->WayBillGenerationStatus->StatusInformation;

                  echo "
                    <div class='divTable'>
                    <div class='headRow'>
                    <div class='divCell pincodes' align='center'>
                    <p><span><b>WayBill Cancellation </b></span></p>
                    <p><span><b>Description :</b></span><span style='color:green;'><b>".$res_status."</b></span></p>
                    </div>
                    </div>
                  </div>";

                 }
                 
                 //echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
                 exit;
            }
            catch (Exception $e) {
                  Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
exit;
}
catch (Exception $e) {
          Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

    }
}
