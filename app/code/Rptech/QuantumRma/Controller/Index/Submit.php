<?php
namespace Rptech\QuantumRma\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Rptech\QuantumRma\Helper\MailHelper;
use Rptech\QuantumRma\Model\QuantumRmaFactory;
use Magento\Framework\Session\SessionManagerInterface;

class Submit extends Action
{
	protected $quantumRmaFactory;
    protected $mailHelper;

    /**
     * @var SessionManagerInterface
     */
    protected $session;

    public function __construct(
        Context $context,
        QuantumRmaFactory $quantumRmaFactory,
        MailHelper $mailHelper,
        SessionManagerInterface $session
    ) {
		$this->quantumRmaFactory = $quantumRmaFactory;
        $this->mailHelper = $mailHelper;
        $this->session = $session;
		parent::__construct($context);
	}

	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
		if ($data) {
			try {
				$model = $this->quantumRmaFactory->create();
				$model->setData($data);
				$model->save();
				$insertId = $model->getId();
				$caseNumber = 'Q-' . str_pad($insertId, 2, '0', STR_PAD_LEFT);
				$model->setCaseNumber($caseNumber);
				$model->save();
                $case_number = "";
				if ($model->getCaseNumber()) {
					$case_number = $model->getCaseNumber();
				}

                $emailTemplateVariables = [
                    "model" => $model
                ];
                $this->mailHelper->mailSend($emailTemplateVariables, [], "data_email");
                $this->session->start();
                $this->session->setQuantumSuccessMessage(
                    "Case Number: {$case_number} - Your form has been submitted successfully."
                );
			} catch (\Exception $e) {
				$this->messageManager->addErrorMessage(__('Unable to submit form.'));
			}
		}
		//return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/quantum-support');
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('quantum-support');
	}
}
