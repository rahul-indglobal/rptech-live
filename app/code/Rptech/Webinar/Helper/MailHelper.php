<?php

namespace Rptech\Webinar\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Translate\Inline\StateInterface;

class MailHelper extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var DirectoryList
     */
    protected $_directoryList;

    /**
     * @var string
     */
    protected $temp_id;

    /**
     * Mail constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        DirectoryList $directoryList,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    )
    {
        $this->_scopeConfig = $context;
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_directoryList = $directoryList;
        $this->_transportBuilder = $transportBuilder;
    }

    /**
     * Return store configuration value of your template field that which id you set for template
     *
     * @param string $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        $storeId = $this->getStore()->getStoreId();
        return $this->scopeConfig->getValue(
            $path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * Return store
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * Return template id according to store
     *
     * @return mixed
     */
    public function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath);
    }

    /**
     * [generateTemplate description]  with template file and tempaltes variables values
     * @param array $emailTemplateVariables
     * @param array $senderInfo
     * @param array $receiverInfo
     * @param array $copyTo
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo, $copyTo)
    {

        $this->_transportBuilder->setTemplateIdentifier($this->temp_id)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->_storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom($senderInfo)
            ->addTo($receiverInfo['email'], $receiverInfo['name']);
        if (!empty($copyTo)) {
            foreach ($copyTo as $email) {
                $this->_transportBuilder->addBcc($email);
            }
        }
        return $this;
    }

    /**
     * Send mail
     *
     * @param array $emailTemplateVariables
     * @param array $receiverInfo
     * @param string $type
     * @return void|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     */
    public function mailSend($emailTemplateVariables, $receiverInfo = [], $type = "otp")
    {
        try {
            if ($type == "otp") {
                $this->temp_id = $this->getConfigValue("rptech/webinar/otp_email_template");
            } else {
                $this->temp_id = $this->getConfigValue("rptech/webinar/data_email_template");
            }

            $this->inlineTranslation->suspend();
            $senderInfo = $this->getSenderInfo();
            if (empty($receiverInfo)) {
                $receiverInfo = $this->getReceiverInfo();
            }
            $copyTo = [];
            if ($type != "otp") {
                $copyTo = $this->getCopyTo();
            }
            $this->generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo, $copyTo);
            $transport = $this->_transportBuilder->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $ex) {
            throw new LocalizedException(
                __($ex->getMessage())
            );
        }
        return true;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->_directoryList->getRoot();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getMediaDir()
    {
        return $this->_directoryList->getPath('media');
    }

    /**
     * @return array
     */
    public function getSenderInfo()
    {
        $indent = $this->getConfigValue("rptech/webinar/sender");
        return [
            "email" => $this->getConfigValue('trans_email/ident_'.$indent.'/email'),
            "name" => $this->getConfigValue('trans_email/ident_'.$indent.'/name'),
        ];

    }

    /**
     * @return array
     */
    public function getReceiverInfo()
    {
        return [
            "email" => $this->getConfigValue("rptech/webinar/recipient"),
            "name" => "Agent",
        ];

    }

    /**
     * @return array
     */
    public function getCopyTo()
    {
        $copyTo = $this->getConfigValue("rptech/webinar/copy_to");
        if (!empty($copyTo)) {
            return array_map('trim', explode(',', $copyTo));
        }
        return [];
    }
}
