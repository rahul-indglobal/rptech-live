<?php
namespace Rptech\Communication\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const WA_TEMPLATE_REGISTRATION = "rpt_general/whatsapp/wa_template_registration";
    const WA_TEMPLATE_NEW_ORDER = "rpt_general/whatsapp/wa_template_new_order";
    const WA_TEMPLATE_SEND_ENQUIRY = "rpt_general/whatsapp/wa_template_send_enq";
    const WA_TEMPLATE_SHIPMENT = "rpt_general/whatsapp/wa_template_shipment";
    const WA_TEMPLATE_REFUND = "rpt_general/whatsapp/wa_template_refund";

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue($config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    private function getToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.rmlconnect.net/auth/v1/login/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "username":"RPTechWBS",
                "password":"RP@Tech123"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // echo "<pre>";
        // print_r(json_decode($response));
        // echo "</pre>";

        //echo $response;
        return json_decode($response);
    }

    public function sendMessage($number, $str)
    {
        $response = $this->getToken();
        print_r($response);
        return $number;
    }

    public function sendRegistrationWhatsAppMessageToCustomer($number, $username, $link)
    {
        $template = $this->getConfig(self::WA_TEMPLATE_REGISTRATION);
        $response = $this->getToken();
        unset($token);
        $token = $response->JWTAUTH;
        
        if(!$template) {
            return false;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.rmlconnect.net/wba/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "phone": "'.$number.'",
                "media": {
                    "type": "media_template",
                    "template_name": "'.$template.'",
                    "lang_code": "en",
                    "body": [
                        {
                            "text": "'.$username.'"
                        },
                        {
                            "text": "'.$link.'"
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendNewOrderWhatsAppMessageToCustomer($number, $username, $orderid, $image, $link)
    {
        $template = $this->getConfig(self::WA_TEMPLATE_NEW_ORDER);
        $response = $this->getToken();
        unset($token);
        $token = $response->JWTAUTH;
        //echo $token;
        //echo "<br>";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.rmlconnect.net/wba/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "phone": "'.$number.'",
                "media": {
                    "type": "media_template",
                    "template_name": "'.$template.'",
                    "lang_code": "en",
                    "header": [
                        {
                            "image": {
                                "link": "'.$image.'"
                            }
                        }
                    ],
                    "body": [
                        {
                            "text": "'.$username.'"
                        },
                        {
                            "text": "'.$orderid.'"
                        },
                        {
                            "text": "'.$link.'"
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendShipmentWhatsAppMessageToCustomer($number, $username, $orderid, $doc, $link)
    {
        $template = $this->getConfig(self::WA_TEMPLATE_SHIPMENT);
        $response = $this->getToken();
        $token = $response->JWTAUTH;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.rmlconnect.net/wba/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "phone": "'.$number.'",
                "media": {
                    "type": "media_template",
                    "template_name": "'.$template.'",
                    "lang_code": "en",
                    "header": [
                        {
                            "document": {
                                "link": "'.$doc.'"
                            }
                        }
                    ],
                    "body": [
                        {
                            "text": "'.$username.'"
                        },
                        {
                            "text": "'.$orderid.'"
                        },
                        {
                            "text": "'.$link.'"
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendRefundWhatsAppMessageToCustomer($number, $username, $orderid, $link)
    {
        $template = $this->getConfig(self::WA_TEMPLATE_REFUND);
        $response = $this->getToken();
        $token = $response->JWTAUTH;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.rmlconnect.net/wba/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "phone": "'.$number.'",
                "media": {
                    "type": "media_template",
                    "template_name": "'.$template.'",
                    "lang_code": "en",
                    "body": [
                        {
                            "text": "'.$username.'"
                        },
                        {
                            "text": "'.$orderid.'"
                        },
                        {
                            "text": "'.$link.'"
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendSendEnuiryWhatsAppMessageToCustomer($number, $username, $prodname, $sku, $price, $image, $link)
    {
        $template = $this->getConfig(self::WA_TEMPLATE_SEND_ENQUIRY);
        $response = $this->getToken();
        $token = $response->JWTAUTH;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apis.rmlconnect.net/wba/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "phone": "'.$number.'",
                "media": {
                    "type": "media_template",
                    "template_name": "'.$template.'",
                    "lang_code": "en",
                    "header": [
                        {
                            "image": {
                                "link": "'.$image.'"
                            }
                        }
                    ],
                    "body": [
                        {
                            "text": "'.$username.'"
                        },
                        {
                            "text": "'.$prodname.'"
                        },
                        {
                            "text": "'.$sku.'"
                        },
                        {
                            "text": "'.$price.'"
                        },
                        {
                            "text": "'.$link.'"
                        }
                    ]
                }
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}