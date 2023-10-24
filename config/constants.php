<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

return [
    'system_user_id' => 1,
    'system_role_id' => 1,

    'site' => [
        'logo_url' => "/images/logo-letter-1.png"
    ],

    'default_sort_field' => 'id',

    'asset_link_expiry' => env('ASSETS_LINK_EXPIRY', (60 * 60 * 24)), // Default 24 hours

    'paginate' => '10',

    'import_csv_log' => [
        'status' => [
            '0' => 'Fail',
            '1' => 'Success',
        ],
        'status_enum' => ['0', '1'],
    ],

    'messages' => [
        'user' => [
            'invalid' => 'Invalid credentials',
        ],

        'success' => 'Success',
        'create_success' => 'Create Successfully',
        'delete_success' => 'Delete Successfully',
        'update_success' => 'Update Successfully',
        'registration_success' => 'Congratulations! You’ve successfully registered.',
        'forgotpassword_success' => 'Password reset instructions has been sent to your email. Please check your inbox/spam',
        'forgotpassword_error' => 'Invalid Email',
        'something_wrong' => 'Something went wrong.',
        'login' => [
            'success' => 'Login is successful.',
            'unverified_account' => 'Your account is not verified yet.',
            'wrong_credentials' => 'Invalid combination of email and password.',
            'login_token_failed' => 'Could not create login token.',
        ],
        'password_changed' => "Password has been changed.",
        'something_went_wrong' => 'Something went wrong.',
        'invalid_old_password' => "Invalid old password.",
        'similar_password' => "Please enter a password which is not similar then current password.",
        'not_match_confirm_password' => "New password is not match to confirm password.",
        'delete_sucess' => 'Delete Successful.',
        'apply_permissions' => 'Role permissions applied successfully.',
        'file_csv_error' => 'please upload csv file',
        'no_data_found' => 'No data found.',
        'token_expire' => 'Invalid token id or token expired.',
        'delete_multiple_error' => 'Please select records',
        'voucher_not_found' => 'Voucher not found',
        'admin_user_delete_error' => 'System user can not be deleted.',
        'voucher' => [
            'voucher_expired' => 'The voucher has been expired.',
            'voucher_not_exists' => 'The voucher does not exists.',
            'voucher_invalid' => 'The voucher is invalid.',
            'voucher_not_found' => 'The voucher is invalid.',
            'voucher_redeem_success' => 'The voucher has been redeemed.',
            'voucher_product_not_exists' => 'The voucher product does not exists.',
        ]
    ],

    'validation_codes' => [
        'unauthorized' => 401,
        'forbidden' => 403,
        'unprocessable_entity' => 422,
        'ok' => 200,
    ],

    'user' => [
        'status' => [
            '0' => 'Inactive',
            '1' => 'Active',
        ],
        'status_code' => [
            'inactive' => '0',
            'active' => '1',
        ],

        'user_type' => [
            '0' => 'Admin',
            '1' => 'User',
        ],

        'user_type_code' => [
            'admin' => '0',
            'user' => '1',
        ],

        'action_type' => [
            'debit' => '0',
            'credit' => '1',
        ],

        'action_type_text' => [
            '0' => 'Debit',
            '1' => 'Credit',
        ],
    ],

    'vouchers' => [
        'voucher_type' => [
            '0' => 'Products',
            '1' => 'Points',
        ],
        'voucher_type_code' => [
            'product' => '0',
            'points' => '1',
        ],

        'status' => [
            '0' => 'Deactive',
            '1' => 'Active',
        ],
        'status_code' => [
            'deactive' => '0',
            'active' => '1',
        ],

        'voucher_redeem' => [
            '0' => 'No',
            '1' => 'Yes',
        ],
        'voucher_redeem_text' => [
            'no' => '0',
            'yes' => '1',
        ],

    ],

    'order' => [
        'status' => [
            '0' => 'Pending',
            '1' => 'In process',
            '2' => 'Shipped',
            '3' => 'Delivered',
            '4' => 'Cancel',
            '99' => 'Embedded order'
        ],

        'status_text' => [
            'pending' => '0',
            'inprocess' => '1',
            'shipped' => '2',
            'delivered' => '3',
            'cancel' => '4',
            'embedded_order' => '99',
        ],

        'payment_method' => [
            '1' => 'Easebuzz'
        ],

        'payment_method_text' => [
            'easebuzz' => '1'
        ],

        'payment_mode' => [
            '0' => 'Debit card',
            '1' => 'Credit card',
            '2' => 'Net Banking',
            '3' => 'UPI',
            '4' => 'Other',
        ],

        'payment_mode_text' => [
            'debit_card' => '0',
            'credit_card' => '1',
            'net_banking' => '2',
            'upi' => '3',
            'other' => '4',
        ]
    ],

    'permission' => [
        'has_permission' => '1',
        'has_not_permission' => '0',
        'role_guard_name' => 'web',
        'user_has_not_permission' => "You don\'t have permission to this functionality",
        'user_already_has_permission' => "Given permission already exists",
        'user_clinic_mapping_error' => "User clinic is not mapped yet",
        'module_error' => "Module not in request",
        'invalid_module_error' => "Invalid Module",
        'validation_error_status_code' => 422,
        'permission_assign_success' => 'Permission assign successfully',
        'permission_revert_success' => 'Permission reverted successfully',
        'permission_revert_failure' => 'Permission revert failed',
        'permission_not_found' => 'Permission not found',
        'role_not_found' => 'Role not found',
    ],

    'role' => [
        'apply_role' => '1',
    ],

    'image' => [
        'dir_path' => '/storage/',
        'default_types' => 'gif|jpg|png|jpeg',
        'user_default_img' => 'images/default.jpg',
        'product_default_img' => 'images/default_product_image.png',
    ],

    'models' => [
        'admin_user_model' => 'admin_user',
        'user_user_model' => 'user_user',
        'product_model' => 'product',
        'voucher_model' => 'voucher',
        'order_model' => 'order',
    ],

    'import_dir_path' => [
        'admin_user_dir_path' => 'import/admin_user/',
        'user_user_dir_path' => 'import/user_user/',
        'product_dir_path' => 'import/product/',
        'voucher_dir_path' => 'import/voucher/',
        'order_dir_path' => 'import/order/',
    ],

    'sample_dir_path' => [
        'sample_admin_users' => 'samples/admin_user_sample.csv',
        'sample_products' => 'samples/product_sample.csv',
        'sample_user_users' => 'samples/user_sample.csv',
        'sample_orders' => 'samples/order_sample.csv',
        'sample_vouchers' => 'samples/voucher_sample.csv',
    ],

    'calender' => [
        'date' => Carbon::now()->toDateString(),
        'date_format' => Carbon::now()->format('Y-m-d'),
        'time' => Carbon::now()->toTimeString(),
        'date_time' => Carbon::now()->toDateTimeString(),
        'start_Of_month' => Carbon::now()->startOfMonth(),
        'last_year_date' => Carbon::now()->subYear()->format('Y-m-d')
    ],

    'date_format' => 'd-m-Y',

    'file' => [
        'name' => Carbon::now('Asia/Kolkata')->format('d_m_Y') . '_' . Carbon::now('Asia/Kolkata')->format('g_i_a'),
    ],

    'products' => [
        'available_status' => [
            '0' => 'Out of stock',
            '1' => 'Available',
        ],
        'available_status_code' => [
            'not_available' => '0',
            'available' => '1',
        ],
    ],

    'allowed_ip_addresses' => [
        'telescope' => env('TELESCOPE_ALLOWED_IP_ADDRESSES'),
    ],

    'token_expiry' => env('TOKEN_EXPIRY', (60 * 60 * 24)), // Default 24 hours


    'default_single_filesize' => 20,
    'default_file_extensions' => ['jpeg', 'jpg', 'png', 'webp'],

    'pinnacle_email' => [
        'service_auth_token' => 'EmailAuth123',
        'user_auth_token' => 'e746d109-4512-4a23-90ff-1be363930a30',
        'email_sender' => 'communications@loyrexsolutions.com',
        'signature' => 'Thanks & Regards',
        'admin_email' => env('ADMIN_EMAIL', 'admin@yopmail.com')
    ],

    'eZee_sms' => [

        'url' => 'http://smsjust.com/sms/user/urlsms.php',
        'username' => 'loyrexsolution',
        'pass' => '@0DYj8k$',
        'senderid' => 'LOYREX',
        'msgtype' => 'TXT',
        'response' => 'Y',
        'dltentityid' => '1501609730000031397',
        'dltheaderid' => 'LOYREX',

        'messages' => [

            'cancel_order' => 'Canceled: Your order number: {{order_id}}comma; has been withdrawn by Team Loyrex. This happens rarely and we apologize for the inconvenience. We will initiate a refund to your account (*if applicable) – Team ' . env('APP_NAME'),
            'inprocess_order' => 'Hi {{first_name}} {{last_name}}comma; We appreciate your patience! Your order number: {{order_id}} is in process. Our team is working hard to deliver your order on time. The further status of the order will be updated to you via SMS and email – Team ' . env('APP_NAME'),
            'shipped_order' => 'Hi {{first_name}} {{last_name}}comma; We are happy to inform youcomma; Your order number: {{order_id}} has been shipped via {{courier_name}}. Your tracking number is: {{tracking_id}} You can track your order on {{courier_link}} – Team ' . env('APP_NAME'),
            'delivered_order' => 'Hi {{first_name}} {{last_name}}comma; Your order number: {{order_id}} has been delivered to your registered address. Hope we made you smile! In case of any queries or concernscomma; please contact us on inquiry@loyrexsolutions.com – Team ' . env('APP_NAME'),

            'cancel_order_tempid' => '1507165701246804276',
            'inprocess_order_tempid' => '1507165701257983272',
            'shipped_order_tempid' => '1507165701295163301',
            'delivered_order_tempid' => '1507165701304805382',

        ]
    ],

    'generate_otp_number' => [
        'otp_number' => rand(100000, 999999),
        'auth_template_id' => ''
    ],

    'merchant_key' => env('MERCHANT_KEY'),
    'salt_key' => env('SALT'),
    # $ENV="test" pointing to the test Enviroments
    # $ENV="prod" pointing to the Live Enviroments
    'env' => env('ENV'),

    // TODO: Remove comment when live the site

    'front_user_login_url' => env('FRONT_URL') . '/products',

    'contact_url' => env('FRONT_URL') . '/home',

    // Remove below comment for testing in local

    /* 'front_user_login_url' => env('APP_URL') . '/products',

    'contact_url' => env('APP_URL') . '/home' */
];
