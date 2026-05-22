<?php
namespace Rptech\Webinar\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Rptech\Webinar\Helper\MailHelper;
use Rptech\Webinar\Model\WebinarFactory;

class Submit extends Action
{
	protected $webinarFactory;
    protected $mailHelper;

	public function __construct(
        Context $context,
        WebinarFactory $webinarFactory,
        MailHelper $mailHelper
    ) {
		$this->webinarFactory = $webinarFactory;
        $this->mailHelper = $mailHelper;
		parent::__construct($context);
	}

	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
		if ($data) {
			try {
				$model = $this->webinarFactory->create();
				$model->setData($data);
				$model->save();

                $emailTemplateVariables = [
                    "model" => $model
                ];
                $this->mailHelper->mailSend($emailTemplateVariables, [], "data_email");

				$this->messageManager->addSuccessMessage(__('Thank you for registering.'));
			} catch (\Exception $e) {
				$this->messageManager->addErrorMessage(__('Unable to submit form.'));
			}
		}
		return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/nvidia-webinar');
	}
}
