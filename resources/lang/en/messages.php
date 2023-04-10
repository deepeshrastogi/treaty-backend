<?php

return [
    'account_address' => [
        'company_name' => 'The company name field is required.',
        'gender' => 'The Gender is required',
        'first_name' => 'The first name field is required.',
        'last_name' => 'The last name field is required.',
        'street_address' => 'The street address field is required.',
        'street_number' => 'The street number field is required.',
        'pin_code' => 'The pin code field is required.',
        'location' => 'The location field is required.',
        'country' => 'The Country is required',
    ],
    'auth_code_expire' => 'The OTP has been expired.',
    'login' => [
        'limit_reached' => 'You reached limit of incorrect login, try after some time',
        'error' => 'The email address or password you entered is incorrect.',
    ],
    'wrong' => 'Some Thing went wrong',
    'file' => [
        'success' => 'File Uploaded Successfully.',
        'delete' => 'File deleted Successfully.',
    ],
    'user' => [
        'exists' => 'User already exists in system',
        'not_in_team' => 'This user is not in your team',
    ],
    'invitation' => [
        'sent' => 'Invitation Sent',
    ],
    'invite_code' => [
        'exists' => 'Invite code already used',
    ],
    'email' => [
        'required' => 'The email is required.',
        'exists' => 'The email address or password you entered is incorrect.',
        'unique' => 'The email has already been taken',
        'email' => 'The email is not in valid format.',
        'distinct' => 'The email field has a duplicate values.',
    ],
    'first_name' => [
        'required' => 'The First Name is required',
        'min' => 'Minimum 2 letter required in First Name',
    ],
    'company_name' => [
        'required_if' => 'The company name is required',
    ],
    'last_name' => [
        'required' => 'The Last Name is required',
        'min' => 'Minimum 2 letter required in Last Name',
    ],
    "auth_code" => [
        'required' => 'The OTP is required.',
    ],
    "token" => [
        'required' => 'The token is required.',
        'exists' => 'This is invalid token.',
        'invalid' => "Invalid auth token",
    ],
    'current_password' => [
        'required' => 'The current password is required.',
        'match' => 'The current password should match the old password.',
    ],
    'new_password' => [
        'required' => 'The New Password is required',
        'min' => 'The new password must be at least :MIN characters.',
        'different' => 'The new password should be different from current password',
    ],
    'confirm_password' => [
        'required' => 'The Confirm Password is required',
        'same' => 'The confirm password and new password must match.',
    ],
    'password' => [
        'required' => 'The Password is required.',
        'min' => 'The password must be at least :MIN characters.',
    ],
    "address" => [
        "company_name" => 'The Company name is required.',
        'address_designation' => 'The destination address is required',
        'additive' => 'The additive is required',
        'road' => 'The road is required',
        'road_no' => 'The road no is required',
        'postcode' => 'The post code is required',
        'place' => 'The place is required',
        'country' => 'The country is required',
        'required' => 'Address is required',
    ],
    "cv" => [
        "street_number" => "Only letters and numbers allowed in street number.",
        "already" => "Your CV already added",
        "not_rights" => 'You do not have permission to edit the CV.',
        "not_exists" => 'This CV is not exists.',
        "delete" => 'CV deleted successfully.',
        "deleted" => 'CV already deleted.',
        "first_name" => [
            "required" => "The first name is required.",
        ],
        "last_name" => [
            "required" => 'The last name is required.',
        ],
        "network" => [
            "required" => 'The network is required.',
        ],
        "url" => [
            "required" => 'The :key is required.',
            'distinct' => 'Duplicate values detected',
        ],
        "cv_contact_details" => [
            "email" => [
                "required" => 'The contact email field is required.',
                "email" => 'The contact email must be a valid email address.',
            ],
            "website" => [
                "url" => "The contact website format is invalid.",
            ],
        ],
        "cv_images" => [
            "image_id" => [
                "exists" => "The images image id field is invalid.",
            ],
            "video_id" => [
                "exists" => "The videos video id field is invalid.",
            ],
        ],
    ],
    "unauthorize" => 'The given information is incorrect.',
    "record" => [
        "heading_text" => "The headline text is required",
        "heading_description" => "The headline description is required",
    ],
    'team_id' => [
        'required' => 'Team id is required',
        'exists' => 'Team is not exists in system',
    ],
    'user_id' => [
        'required' => 'User ID is required',
        'active' => 'Active is required',
    ],
    'order' => [
        'required' => 'Order id is required',
        'exists' => 'Order ID does not exist',
        'deleted' => 'Order is successfully deleted',
        'order_data' => "Order_data can't be emptied",
        'user_id' => "User id is required",
    ],
    'file' => [
        'required' => 'File id is required',
        'exists' => "File id doesn't exist",
        'deleted' => 'File deleted successfully',
    ],
    'order_file' => [
        'required' => 'Add file',
        'mimes' => 'This file type is not allowed',
    ],
    'order_status' => [
        'process' => 'In progress',
        'open' => 'open',
        'completed' => 'completed',
    ],
    'talkToExpt' => [
        'send' => 'Email sent successfully',
        'notSend' => 'sorry, the email sent failed',
    ],
    'password_update' => 'The Password updated successfully',
    'password_reset' => 'The Password reset link sent on your email',
    'user_created' => 'User created successfully',
    'user_logout' => 'Successfully logged out',
    'invalid_token' => 'This is an invalid token',
    'orders_code' => [
        'update' => 'Bestellcode erfolgreich aktualisiert',
        'already_update_order' => 'All order codes have been already updated',
    ],
    'order_id' => [
        'required' => 'Order id is required',
        'file_exists' => 'This order has no files',
    ],
   'subsidary_name' => [
        'required' => 'Subsidiary name is required',
        'unique' => 'The subsidiary name has already been taken',
    ],
    'location_id' => [
        'required' => 'Location is required',
    ],
    'po_box' => [
        'required' => 'P.O Box is required',
    ],
];
