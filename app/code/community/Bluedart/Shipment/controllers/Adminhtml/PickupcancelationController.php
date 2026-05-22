<?php
class Bluedart_Shipment_Adminhtml_PickupcancelationController extends Mage_Adminhtml_Controller_Action
{
    	public function indexAction()
        {
         $this->loadLayout();
    	   $this->_title($this->__("Blue Dart"));
    	   $this->renderLayout();
        }
      public function PickupcancelAction(){
          // $url = 'http://basic.bluedart.com/index.php/admin_shipment/adminhtml_pickupregistration/index/key/fd58c32b29f2b737d278ba53bd1194c2/';
          $reg_date1 = $this->getRequest()->getParam('reg_date');
          $token_no = $this->getRequest()->getParam('token_no');
          $remarks = $this->getRequest()->getParam('remarks');
          $reg_date = $reg_date1.'T00:00:00+00:00';

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
            $pickupregistrationserviceurl = Mage::getStoreConfig('bluedart/url/pickupregistrationservice');
            if(!$pickupregistrationserviceurl)
			{
				echo "Please enter Pickup Registration Service URL from system configuration.";
				exit;
			}
			$soap = new SoapClient($pickupregistrationserviceurl.'?wsdl',
            array(
            'trace'               => 1,  
            'style'               => SOAP_DOCUMENT,
            'use'                 => SOAP_LITERAL,
            'soap_version'        => SOAP_1_2
            ));
            
            $soap->__setLocation($pickupregistrationserviceurl);
            
            $soap->sendRequest = true;
            $soap->printRequest = false;
            $soap->formatXML = true;
            
            //http://netconnect.bluedart.com/Ver1.7/Demo/ShippingAPI/Pickup/PickupRegistrationService.svc
            
            $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IPickupRegistration/CancelPickup',true);
            $soap->__setSoapHeaders($actionHeader);
        
            $params = array(
              'request' => 
                array (
                //  'AWBNo' =>array('58400031395'),
                  'PickupRegistrationDate' => $reg_date1,
                  'Remarks' =>$remarks,
                  'TokenNumber' =>$token_no
                  ),
                'profile' => 
                   array(
                          'Api_type' => $apitype,                          
                          'LicenceKey'=>$licencekey,
                          'LoginID'=>$loginid,
                          'Version'=>$version)
                          );

            try{

                 $StatusInformation = "";
                 $StatusCode = "";
                 $result = $soap->__soapCall('CancelPickup',array($params));
                 $StatusCode = $result->CancelPickupResult->Status->CancelPickupResponseStatus->StatusCode;
                 $StatusInformation = $result->CancelPickupResult->Status->CancelPickupResponseStatus->StatusInformation;

                if($StatusInformation) { 
                  $res_error = $StatusCode." : ".$StatusInformation;
                } else {
                  $res_error = $StatusCode;
                }
                
              if($res_error == 'CancelSuccess')
              {
                echo "
                  <div class='divTable'>
                  <div class='headRow'>
                  <div class='divCell pincodes' align='center'>
                  <p><span><b>Cancel Pickup</b></span></p>
                  <p><span><b>IsError :</b></span><span class='error_msg'>False</span></p>
                  <p><span><b>Error Message :</b></span><span class='error_msg'>".$res_error."</span></p>
                  </div>
                  </div>
                  </div>";
              }
              else
              {
                echo "
                  <div class='divTable'>
                  <div class='headRow'>
                  <div class='divCell pincodes' align='center'>
                  <p><span><b>Cancel Pickup</b></span></p>
                  <p><span><b>IsError :</b></span><span class='error_msg'>True</span></p>
                  <p><span><b>Error Message :</b></span><span class='error_msg'>".$res_error."</span></p>
                  </div>
                  </div>
                  </div>";
              }
              exit;
            }
            catch (Exception $e) {
              echo $e;
            }
        exit;
        }
        catch (Exception $e) {
          echo $e;
        }

    }
}
