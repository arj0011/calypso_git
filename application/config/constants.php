<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/* application development all constants */

switch ($_SERVER['SERVER_NAME']) {
    case 'game-xpress.mobi96.com':
        define('MW_DB_HOST', 'localhost');
        define('MW_DB_USERNAME', 'mobi96_game-xpre');
        define('MW_DB_PASSWORD', 'game-xpress');
        define('MW_DB_DATABASE_NAME', 'mobi96_game-xpress');
        $host     = $_SERVER['HTTP_HOST'];
        break;
    default:
        define('MW_DB_HOST', 'localhost');
        define('MW_DB_USERNAME', 'root');
        define('MW_DB_PASSWORD', 'root');
        define('MW_DB_DATABASE_NAME', 'calypso_db');
        $host     = $_SERVER['HTTP_HOST'] . "/calypso/";
}
define('THEME_BUTTON', 'btn btn-primary');
define('THEME', ''); // skin-1, skin-2, skin-3
define('TO_EMAIL', 'arjun.mobiwebtech@gmail.com');
define('FROM_EMAIL', 'arjun@mobiwebtech.com');
define('SUPPORT_EMAIL', 'arjun@mobiwebtech.com');
define('SITE_NAME', 'Calypso');
define('DEFAULT_USER_IMG', 'default-148.png');
define('LOADER_IMG_PATH', 'backend_asset/images/Preloader_3.gif');
define('DEFAULT_USER_IMG_PATH', 'backend_asset/images/default-148.png');
define('DEFAULT_NO_IMG', 'noimagefound.jpg');
define('DEFAULT_NO_IMG_PATH', 'backend_asset/images/no_image.jpg');
// define('EDIT_ICON', 'backend_asset/images/');
define('EDIT_ICON', 'backend_asset/images/edit1.png');
define('DELETE_ICON', 'backend_asset/images/delete.png');
define('ACTIVE_ICON', 'backend_asset/images/active.png');
define('INACTIVE_ICON', 'backend_asset/images/inactive.png');
define('VIEW_ICON', 'backend_asset/images/eye.png');
define('PASSWORD_ICON', 'backend_asset/images/key.png');
define('CUSTOMCSS', 'backend_asset/css/custom.css');
define('SITE_TITLE','Calypso');
define('COPYRIGHT','Calypso &copy; 2017-2018');
define('ADMIN_EMAIL', 'admin@site.com');
define('ADMIN_ID', '1');

/* IOS push notification */
define('APNS_GATEWAY_URL', 'ssl://gateway.sandbox.push.apple.com:2195');
define('APNS_CERTIFICATE_PATH', "/libraries/Dev_MW_GameXpress.pem");

/* Android push notification */
define('ANDROID_SERVER_KEY', 'AAAAh7S2qgg:APA91bEYjcr49F-7ron9aSSLlM9nxBsXyk0rJn9XErT39wEo9McvOUfs7mnmKxBkS3ZtDm1rcYKRDp-hMj6-Bh8I8PzqMlMkbcNX6FZBq5ef4BKtZZOoe7nN7PZkrv-rfWeS4BmsHHz-');
define('ANDROID_NOTIFICATION_URL', 'https://fcm.googleapis.com/fcm/send');

/* Messages constants */
define('GENERAL_ERROR', 'Some error occured, please try again.');
define('EMAIL_SEND_FAILED', 'Failed to sending a mail');
define('NO_CHANGES', 'We didn`t find any changes');
define('USER_VERIFICATION', 'Currently your profile is not verified, please verfiy your email id');
define('BLOCK_USER', 'Your profile has been blocked. Please contact to our support team');
define('DEACTIVATE_USER', 'Currently your profile is deactivated. Please contact to our support team');

/*SMS API Credentials*/

define("OTP_SMS_URL", 'https://www.indiansmsgateway.in/SendSMS/sendmsg.php');
define("OTP_SMS_UNAME", 'carolotp');
define("OTP_SMS_PWD", 'x@4Yj@0I');
define("OTP_SMS_SEND", 'CLYPSO');
define("OTP_SMS_MSG", 'Your 4 digit otp for calypso is ');
define("REDEEMCODE_SMS_MSG", 'Your redeemption code for calypso is ');
//Forget Password message
define("FP_SMS_MSG", 'Your forget password code for calypso is ');


define("SMS_UNAME", 'carolr');
define("SMS_PWD", 'g~8Dq@6K');
define("SMS_SEND", 'UCAROL');

/* Database tables */
define("USERS", 'users');
define("SETTING", 'setting');
define('USERS_DEVICE_HISTORY', 'users_device_history');
define('COUNTRY', 'countries');
define('ADMIN_NOTIFICATION', 'admin_notifications');
define('USER_NOTIFICATION','users_notifications');
define("CMS", 'cms');
define("USER_MEMBERSHIP", 'user_membership');
define("CATEGORY_MANAGEMENT", 'category_management');
define("PRODUCTS", 'products');
define("MEMBERSHIP_TOKEN", 'membership_token');
define("WALLET", 'wallet');
define("ORDERS", 'orders');
define("ORDER_META", 'order_meta');
define("KEY_CONFIGURATION", 'key_configuration');
define("CART_PRODUCT", 'cart_product');
define("OFFERS", 'offers');
define("CONTACT_SUPPORT", 'contact_support');
define("USER_OFFERS", 'user_offers');
define("CONFIGURE_PRODUCTS", 'configure_products');
define("FOODITEMS", 'fooditems');
define("ALLACART", 'alla_cart');
define("FOODPARCEL", 'food_parcel');
define("PARTYPACKAGE", 'party_package');
define("PACKAGECATEGORY", 'package_category');
define("PRICEOFFERS", 'priceOffers');
define("ITEMDATES", 'item_dates');
define("ITEMSDATESDAY", 'items_dates_day');
define("ITEMSDATESDAYSPRICE", 'items_dates_days_price');
define("EVENT", 'event');
define("USERADDRESS", 'user_address');
define("PARCELITEMS", 'parcel_items');
define("ORDER", 'order');
define("CART", 'cart');
define("USERWALLET", 'user_wallet');
define("SUGGESTION_FEEDBACK", 'suggestion_feedback');
define("BILLINGOFFER", 'billing_offer');
define("NOTIFICATIONS", 'notifications');
define("DEVICEHISTORY", 'device_history');







