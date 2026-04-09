<?php
// controller/AdminController.php

require_once __DIR__ . '/../model/AdminModel.php';

class AdminController
{
    private AdminModel $model;

    public function __construct()
    {
        $this->model = new AdminModel();
    }

    public function addOfficial(array $data): array
    {
        // ===== VALIDATION =====
        $errors = [];

        // First Name
        if (empty($data['fname'])) {
            $errors[] = 'First name is required';
        } elseif (strlen($data['fname']) < 2) {
            $errors[] = 'First name must be at least 2 characters';
        }

        // Last Name
        if (empty($data['lname'])) {
            $errors[] = 'Last name is required';
        } elseif (strlen($data['lname']) < 2) {
            $errors[] = 'Last name must be at least 2 characters';
        }

        // Date of Birth
        if (empty($data['dob'])) {
            $errors[] = 'Date of birth is required';
        }

        // Place of Birth
        if (empty($data['pob'])) {
            $errors[] = 'Place of birth is required';
        }

        // Civil Status
        if (empty($data['cs'])) {
            $errors[] = 'Civil status is required';
        }

        // Email
        if (empty($data['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        } elseif ($this->model->emailExists($data['email'])) {
            $errors[] = 'Email already exists';
        }

        // Contact
        if (empty($data['contact'])) {
            $errors[] = 'Contact is required';
        } elseif (!preg_match('/^09\d{9}$/', $data['contact'])) {
            $errors[] = 'Contact must be 11 digits starting with 09';
        }

        // Position
        if (empty($data['position'])) {
            $errors[] = 'Position is required';
        }

        // BarangayX
        if (empty($data['brgy'])) {
            $errors[] = 'Barangay is required';
        } else {
            $brgyId = $this->model->brgyExists($data['brgy']);
            if (!$brgyId) {
                $errors[] = 'Barangay not found';
            }
        }

        // Return errors if any
        if (!empty($errors)) {
            return [
                'status' => 'error',
                'message' => '<p class="text-red-500">' . implode('<br>', $errors) . '</p>'
            ];
        }

        // ===== INSERT =====
        $fname = htmlspecialchars(trim($data['fname']));
        $lname = htmlspecialchars(trim($data['lname']));
        $mname = !empty($data['mname']) ? htmlspecialchars(trim($data['mname'])) : null;
        $dob = trim($data['dob']);
        $pob = htmlspecialchars(trim($data['pob']));
        $cs = trim($data['cs']);
        $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $contact = trim($data['contact']);
        $position = htmlspecialchars(trim($data['position']));
        $title = !empty($data['otitle']) ? htmlspecialchars(trim($data['otitle'])) : null;

        $success = $this->model->addOfficials(
            $fname,
            $lname,
            $mname,
            $dob,
            $pob,
            $cs,
            $email,
            $contact,
            $position,
            $brgyId,
            $title
        );

        if ($success) {
            return [
                'status' => 'success',
                'message' => '<p class="text-green-500">Official added successfully!</p>'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => '<p class="text-red-500">Failed to add official</p>'
            ];
        }
    }
}