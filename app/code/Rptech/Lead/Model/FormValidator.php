<?php

namespace Rptech\Lead\Model;

use Magento\Framework\Exception\InputException;

class FormValidator
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

		// First Name
		if (empty($data['first_name']) || strlen($data['first_name']) < 3) {
			$errors[] = __('First name must be at least 3 characters.');
		} elseif (!$lettersOnly($data['first_name'])) {
			$errors[] = __('First name can contain only letters, spaces, or hyphens.');
		}
		if ($htmlCheck($data['first_name'])) {
			$errors[] = __('HTML or script tags are not allowed in first name.');
		}

		// Last Name
		if (empty($data['last_name']) || strlen($data['last_name']) < 3) {
			$errors[] = __('Last name must be at least 3 characters.');
		} elseif (!$lettersOnly($data['last_name'])) {
			$errors[] = __('Last name can contain only letters, spaces, or hyphens.');
		}
		if ($htmlCheck($data['last_name'])) {
			$errors[] = __('HTML or script tags are not allowed in last name.');
		}

		// Email
		if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = __('A valid email address is required.');
		}
		if ($htmlCheck($data['email'])) {
			$errors[] = __('HTML or script tags are not allowed in email.');
		}

		/** ------------------------------
		 * Optional Fields
		--------------------------------*/

		// Phone (optional but must be exactly 10 digits if provided)
		if (!empty($data['phone'])) {
			if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
				$errors[] = __('Phone number must be exactly 10 digits.');
			}
			if ($htmlCheck($data['phone'])) {
				$errors[] = __('HTML or script tags are not allowed in phone.');
			}
		}

		// Company (optional but min 3 chars if provided, letters only)
		if (!empty($data['company'])) {
			if (strlen($data['company']) < 3) {
				$errors[] = __('Company must be at least 3 characters if provided.');
			} elseif (!$lettersOnly($data['company'])) {
				$errors[] = __('Company can contain only letters, spaces, or hyphens.');
			}
			if ($htmlCheck($data['company'])) {
				$errors[] = __('HTML or script tags are not allowed in company.');
			}
		}

		/** ------------------------------ */

		if ($errors) {
			throw new InputException(__(implode("\n", $errors)));
		}

		return true;
	}

}
