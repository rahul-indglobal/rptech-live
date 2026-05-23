<?php

namespace Rptech\Lead\Model;

use Magento\Framework\Exception\InputException;

class FormValidatorEnquiry
{
	public function validate(array $data)
	{
		// Sanitize input
		$data = array_map('trim', $data);
		$data = array_map('strip_tags', $data);

		$errors = [];

		// Helper: check if HTML or script was attempted
		$htmlCheck = function($value) {
			return preg_match('/<[^>]*>|script/mi', $value);
		};

		// Helper: check if value contains only letters, spaces, or hyphens
		$lettersOnly = function($value) {
			return preg_match("/^[a-zA-Z\s\-]+$/", $value);
		};

		/** ------------------------------
		 * Required Fields
		--------------------------------*/

		// Full Name
		if (empty($data['full_name']) || strlen($data['full_name']) < 3) {
			$errors[] = __('Full name must be at least 3 characters.');
		} elseif (!$lettersOnly($data['full_name'])) {
			$errors[] = __('Full name can contain only letters, spaces, or hyphens.');
		}
		if ($htmlCheck($data['full_name'])) {
			$errors[] = __('HTML or script tags are not allowed in full name.');
		}

		// Email
		if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = __('A valid email address is required.');
		}
		if ($htmlCheck($data['email'])) {
			$errors[] = __('HTML or script tags are not allowed in email.');
		}

		// Company
		if (empty($data['company']) || strlen($data['company']) < 3) {
			$errors[] = __('Company must be at least 3 characters.');
		}
		if ($htmlCheck($data['company'])) {
			$errors[] = __('HTML or script tags are not allowed in company.');
		}

		// Role
		if (empty($data['role']) || strlen($data['role']) < 2) {
			$errors[] = __('Role must be at least 2 characters.');
		}
		if ($htmlCheck($data['role'])) {
			$errors[] = __('HTML or script tags are not allowed in role.');
		}

		// Industry
		if (empty($data['industry']) || strlen($data['industry']) < 2) {
			$errors[] = __('Industry must be at least 2 characters.');
		}
		if ($htmlCheck($data['industry'])) {
			$errors[] = __('HTML or script tags are not allowed in industry.');
		}

		// Role Other
		if (!empty($data['role_other']) && $htmlCheck($data['role_other'])) {
			$errors[] = __('HTML or script tags are not allowed in other role.');
		}

		// Industry Other
		if (!empty($data['industry_other']) && $htmlCheck($data['industry_other'])) {
			$errors[] = __('HTML or script tags are not allowed in other industry.');
		}

		/** ------------------------------ */

		if ($errors) {
			throw new InputException(__(implode("<br>", $errors)));
		}

		return true;
	}

}
