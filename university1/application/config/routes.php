<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */



/* admin login, dashboard and forgot password */
$route['admin'] = "admin/index";
$route['backend/change-languageversion-for-functionality'] = "admin/change_language_for_functionality";

$route['backend'] = "admin/index";
$route['backend/login'] = "admin/index";
$route['backend/index'] = "admin/index";
$route['backend/home'] = "admin/home";
$route['backend/dashboard'] = "admin/home";
$route['backend/log-out'] = "admin/logout";
$route['backend/forgot-password'] = "admin/forgotPassword";
$route['backend/reset-password/(:any)'] = "admin/resetPassword/$1";
$route['backend/forgot-password-email'] = "admin/checkForgotPasswordEmail";
/* admin login, dashboard and forgot password end here */

/* Global Settings:   */
$route['backend/global-settings/list'] = "global_setting/listGlobalSettings";
$route['backend/global-settings/edit/(:any)'] = "global_setting/editGlobalSettings/$1/$2";
$route['backend/global-settings/edit-parameter-language/(:any)'] = "global_setting/editParameterLanguage/$1";
$route['backend/global-settings/get-global-parameter-language'] = "global_setting/getGlobalParameterLanguage";
/* Global Settings End Here */

/* Manage Role: */
$route['backend/role/list'] = "role/listRole";
$route['backend/role/edit/(:any)'] = "role/addRole/$1";
$route['backend/role/add'] = "role/addRole";
$route['backend/role/check-role'] = "role/checkRole";
/* Manage Role End Here */

/* Manage Admin  */
$route['backend/admin/list'] = "admin/listAdmin";
$route['backend/admin/change-status'] = "admin/changeStatus";
$route['backend/admin/add'] = "admin/addAdmin";
$route['backend/admin/check-admin-username'] = "admin/checkAdminUsername";
$route['backend/admin/check-admin-email'] = "admin/checkAdminEmail";
$route['backend/admin/account-activate/(:any)'] = "admin/activateAccount/$1";
$route['backend/admin/edit/(:any)'] = "admin/editAdmin/$1";
$route['backend/admin/profile'] = "admin/adminProfile";
$route['backend/admin/edit-profile'] = "admin/editProfile";
$route['backend/country-change-language/(:any)'] = "countries/changeLanguage/$1";
$route['backend/country/country-name'] = "countries/getAllCountryNames";
/* Manage Admin End Here */

/*
 * Manage User Start Here
 */
$route['backend/user/list'] = "user/listUser";
$route['backend/user/change-status'] = "user/changeStatus";
$route['backend/user/add'] = "user/addUser";
$route['backend/user/check-user-username'] = "user/checkUserUsername";
$route['backend/user/check-user-email'] = "user/checkUserEmail";
$route['backend/user/account-activate/(:any)'] = "user/activateAccount/$1";
$route['backend/user/edit/(:any)'] = "user/editUser/$1";
$route['backend/user/view/(:any)'] = "user/userProfile/$1";
//$route['backend/user/address/view/(:any)'] = "user/userAddress/$1";

$route['backend/user/address/current-address-view/(:any)'] = "user/userCurrentAddress/$1";
$route['backend/user/address/permant-address-view/(:any)'] = "user/userPermantAddress/$1";
$route['backend/user/address/office-address-view/(:any)'] = "user/userOfficeAddress/$1";
$route['backend/user/address/forward-address-view/(:any)'] = "user/userForwardAddress/$1";

$route['backend/user/delete-user/(:any)'] = "user/deleteUser/$1";
$route['backend/user/reverification-link/(:any)'] = "user/reverificationLink/$1/$2";
$route['backend/user/change-status-email'] = "user/changeStatusEmail";
/*
 * Manage User End Here
 */

/*
 * Manage email template routes
 */
$route['backend/email-template/list'] = "email_template/index";
$route['backend/edit-email-template/(:any)'] = "email_template/editEmailTemplate/$1";

/*
 * Manage email template routes end
 */

/* Manage Contact Us Start Here */
$route['backend/contact-us'] = "contact_us/listContactUs";
$route['backend/contact-us/view/(:any)'] = "contact_us/view/$1";
$route['backend/contact-us/reply/(:any)'] = "contact_us/reply/$1";
/* Manage Contact Us ENd Here */

/* Manage CMS Start Here */
$route['backend/cms'] = "cms/listCMS";
$route['backend/cms/edit-cms/(:any)'] = "cms/editCMS/$1";
$route['backend/cms/edit-cms-language/(:any)'] = "cms/editCmsLanguage/$1";
$route['backend/cms/get-cms-language'] = "cms/getCmsLanguage";
/* * Common  Editor validation while uploading image file  route  here * */
$route['upload-image'] = "cms/uploadImage";
/* Manage Contact Us End Here */


/* * * Manage Categories * */
$route['backend/categories/list'] = "category/listCategory";
$route['backend/category/add-category'] = "category/addCategory";
$route['backend/category/edit-category/(:any)'] = "category/editCategory/$1";
$route['backend/change-category-language/(:any)'] = "category/changeLanguage/$1";
$route['backend/category/check-category-name'] = "category/checkCategoryName";
$route['backend/category/category-name'] = "category/getAllCategoryNames";
$route['backend/categories/validate-page-url'] = "category/validatePageUrl";
$route['backend/categories/get-language-for-categories'] = "category/getLanguageForCategories";
$route['category-detail/(:any)'] = "category/handleCleanUrls/$1";
$route['categories-home/(:any)'] = "category/categoriesHome/$1";
$route['categories-home'] = "category/categoriesHome";

/** Manage Blogs at backend start here  */
$route['backend/blog'] = "blog/manage_blog_posts";
$route['backend/blog/add-post'] = "blog/edit_post";
$route['backend/blog/edit-post/(:any)'] = "blog/edit_post/$1";
$route['backend/blog/delete-post'] = "blog/delete_post";
$route['backend/blog/view-comments/(:any)'] = "blog/view_post_comments/$1";
$route['backend/blog/add-post-comment/(:any)'] = "blog/add_post_comment/$1";
$route['backend/blog/edit-post-comment/(:any)/(:any)'] = "blog/edit_post_comment/$1/$2";
$route['backend/blog/delete-post-comment'] = "blog/delete_post_comment";
$route['backend/blog/lang-posts/(:any)'] = "blog/lang_post/$1";
$route['backend/blog/get-language-for-posts'] = "blog/get_language_for_posts";
$route['backend/blog/blog-category'] = "blog/blogCategory";
$route['backend/blog/check-blog-category'] = "blog/checkCategoryName";
$route['backend/blog/check-blog-edit-category'] = "blog/checkEditCategoryName";
$route['backend/blog/add-category'] = "blog/add_category";
$route['backend/blog/delete-category'] = "blog/delete_category";
$route['backend/blog/edit-category/(:any)'] = "blog/edit_category/$1";
$route['backend/blog/author-list'] = "blog/blog_author";
$route['backend/blog/add-author'] = "blog/add_author";
$route['backend/blog/delete-author'] = "blog/delete_author";
$route['backend/blog/edit-author/(:any)'] = "blog/edit_author/$1";
$route['backend/blog/validate-page-url'] = "blog/validatePageUrl";
/** Manage Blogs at backend  end here  */
/* Manage FAQs Backend side Start */
$route['backend/faqs/list'] = "faqs/listFAQS";
$route['backend/faqs/add'] = "faqs/addFAQ";
$route['backend/faqs/add/(:any)'] = "faqs/addFAQ/$1";
$route['backend/faqs/lang-faq/(:any)'] = "faqs/addLangFAQ/$1";
$route['backend/faqs/delete'] = "faqs/deleteFAQ";
$route['backend/faqs/categories'] = "faqs/getFaqCategories";
$route['backend/faqs/lang-category/(:any)'] = "faqs/langCategory/$1";
$route['backend/faqs/get-language-for-faq'] = "faqs/getLanguageForFaq";
$route['backend/faqs/validate-page-url'] = "faqs/validatePageUrl";
$route['backend/faqs/get-language-for-categories'] = "faqs/getLanguageForCategories";
$route['backend/faqs/delete-category'] = "faqs/deleteCategory";
$route['backend/faqs/add-category'] = "faqs/addFaqCategories";
$route['backend/faqs/add-category/(:any)'] = "faqs/addFaqCategories/$1";
$route['backend/faqs/check-duplicate-category-name'] = "faqs/checkDuplicateCategoryName";
$route['backend/faqs/change-status'] = "faqs/changeStatus";
/* Manage FAQs Backend side End */

/* Manage FAQs Backend side End */
$route['faqs'] = "faqs/index";
$route['faqs/(:any)'] = "faqs/index/$1";
$route['search-faq'] = "faqs/searchFaq";
/* Manage FAQs Backend side End */

/* Newsletter Start-Here */
$route['backend/newsletter/list'] = "newsletter/listNewsletter";
$route['backend/newsletter/add'] = "newsletter/addNewsletter";
$route['backend/newsletter/change-status'] = "newsletter/changeStatus";
$route['backend/newsletter/edit/(:any)'] = "newsletter/editNewsletter/$1";
$route['backend/send-newsletter/(:any)'] = "newsletter/sendNewsletter/$1";
$route['backend/newsletter/upload-cleditor-image'] = "newsletter/uploadClEditorImage";
/* Newsletter End-Here */

/* * * newletter subscription *** */
$route['backend/newsletter-subscriber/list'] = "subscriber_newsletter/listSubscriberNewsletter";
$route['backend/subscriber-newsletter/change-status'] = "subscriber_newsletter/changeStatus";

/* Manage countries here */
$route['backend/countries'] = "countries/countriesList";
$route['backend/manage-countries'] = "countries/deleteCountriesList";
$route['backend/delete-countries'] = "countries/deleteCountriesList";
$route['backend/countries/edit-country/(:any)'] = "countries/editCountry/$1";
$route['backend/countries/add'] = "countries/addCountry";
$route['backend/check-country-name'] = "countries/checkCountryName";
$route['backend/check-country-iso'] = "countries/checkCountryIso";
$route['backend/check-country-phone-code'] = "countries/checkCountryPhoneCode";
/* Manage countries end */

/* Manage States here */
$route['backend/states'] = "states/statesList";
$route['backend/delete-states'] = "states/deleteStatesList";
$route['backend/states/add'] = "states/addState";
$route['backend/states/edit-states/(:any)'] = "states/editStates/$1";
$route['backend/check-states-name'] = "states/checkStatesName";

$route['backend/state-change-language/(:any)'] = "states/changeLanguage/$1";
$route['backend/state/state-name'] = "states/getAllStateNames";
/* Manage States end */

/* Manage Cities */
$route['backend/cities'] = "city/listCity";
$route['backend/manage-cities'] = "city/deleteCity";
$route['backend/cities/add'] = "city/addCity";
$route['backend/cities/edit-city/(:any)'] = "city/editCity/$1";
$route['backend/check-city-name'] = "city/checkCityName";
$route['backend/get-state-info'] = "city/getAllStateInfo";

$route['backend/city-change-language/(:any)'] = "city/changeLanguage/$1";
$route['backend/city/get-all-city-names'] = "city/getAllCityNames";
$route['backend/city/get-city-name'] = "city/getCityName";


/* Manage Cities end */



/* Testimonial Routes backend */
$route['backend/testimonial/list'] = "testimonial/listTestimonial";
$route['backend/testimonial/change-status'] = "testimonial/changeStatus";
$route['backend/testimonial/add/(:any)'] = "testimonial/addTestimonial/$1";
$route['backend/testimonial/add'] = "testimonial/addTestimonial";
$route['backend/testimonial/change-homepage-testimonial-status'] = "testimonial/changeHomePageTestimonialStatus";




/* Database Error Page */
$route['page-not-found'] = "cms/databaseError";
/* Database Error Page */

/* FrontEnd Routes Start */

$route['default_controller'] = "home/index";

/*
 *  User account section start 
 */
$route['profile'] = "user_account/profile";
$route['profile/edit/(:any)'] = "user_account/editProfile/$1";
$route['chk-valid-password'] = "user_account/validatePassword";
$route['chk-edit-email-duplicate'] = "user_account/chkEditEmailDuplicate";
$route['chk-edit-username-duplicate'] = "user_account/chkEditUsernameDuplicate";
$route['profile/account-setting'] = "user_account/accountSetting";
$route['generate-new-password-code'] = "user_account/generateNewPasswordCode";
$route['check_for_valid_code'] = "user_account/checkForValidCodeOnchangePassword";
$route['check_for_valid_code_reset'] = "register/checkForValidCodeOnchangePassword";
$route['change-password'] = "user_account/changePassword";
$route['edit-user-password-chk'] = "user_account/editUserPasswordChk";
$route['profile/change-profile-picture'] = "user_account/changeProfilePicture";
$route['logout'] = "user_account/logout";


/*
  complete-profile
 *  */

$route['complete-profile/(:any)'] = "register/completeProfile/$1";

/*
  complete-profile end
 *  */

/*
  View Address Details
 *  */

$route['view-address/(:any)'] = "address/viewAddress/$1";
$route['address/edit/(:any)'] = "address/editAddress/$1/$2";
$route['update-address'] = "address/updateAddress";
$route['office-address'] = "address/officeAddress";
$route['forwarding-address/add/(:any)'] = "address/addForwardingAddress/$1";
$route['view-forwarding-address/(:any)'] = "address/viewForwardingAddress/$1";
$route['edit-forwarding-address/(:any)'] = "address/editForwardingAddress/$1/$2";
/*
  View Address Details  end
 *  */

/*  User Contact Start Here  */

$route['contacts'] = "contacts/userContacts";
$route['search-contact'] = "contacts/searchContacts";
$route['edit-contact/(:any)'] = "contacts/editContact/$1";
/*  User Contact End Here  */







/*
  Backend organization
 *  */

$route['backend/document/list'] = "organization/documentList";
$route['backend/document/add'] = "organization/documentAdd";
$route['backend/document/add/(:any)'] = "organization/documentAdd/$1";
$route['chk-document'] = "organization/chkDocumentExits";
$route['backend/document/delete'] = "organization/deleteDocument";
$route['backend/organization/list'] = "organization/organizationList";
$route['backend/organization/change-status'] = "organization/changeStatus";
$route['backend/organization/add'] = "organization/organizationAdd";
$route['backend/organization/add/(:any)'] = "organization/organizationAdd/$1";
$route['chk-orgnisation-name'] = "organization/organizationName";
$route['backend/get-category-info'] = "organization/categoryInfo";
/*
 *  User account section end
 */

/** Manage Blogs at fron * */
$route['blog/add-comment'] = "blog/add_comment";
$route['blog/(:any)'] = "blog/index/$1";
$route['blog'] = "blog/index";
$route['blog/add-post-front'] = "blog/add_post_data";
$route['blogs/author-details/(:any)'] = "blog/author_details/$1";
$route['blogs/year-details/(:any)/(:any)'] = "blog/year_details/$1/$2";
/* Revision 2 routes end here */

/** Advertise front route start here * */
$route['advertise-image'] = "advertise/advertiseImage";

$route['cms/(:any)'] = "cms/cmsPage/$1";

$route['contact-us'] = "contact_us/index";

/* * Notification Managed frontend Start* */
$route['my-notification'] = "notification/my_notification";
$route['my-notification/(:any)'] = "notification/my_notification/$1";
$route['notification-details/(:any)'] = "notification/notification_details/$1";
$route['delete-notification/(:any)'] = "notification/delete_notification/$1";
/* * Notification Manage frontend End* */


/* Registration Process Step */
$route['first-step-registration'] = 'register/firstStepMobileNumber';
$route['second-step-registration'] = 'register/secondStepMobileNumber';
$route['third-step-registration'] = 'register/thirdStepCompleteProfile';
$route['forth-step-registration'] = 'register/forthStepCurrentAddress';
$route['fifth-step-registration'] = 'register/fifthStepPermanentAddress';
$route['sixth-step-registration'] = 'register/sixthStepOfficeAddress';
$route['chk-number-duplicate'] = 'register/chkNumberDuplicate';
$route['chk-number-exists'] = 'register/chkNumberExists';
$route['sign-up'] = "register/registration";
$route['sign-in'] = "register/signin";
$route['resend-otp'] = "register/resendOtp";
$route['fb-signup'] = "register/fbUserRegistration";
$route['chk-email-duplicate'] = "register/chkEmailDuplicate";
$route['chk-email-exist'] = "register/chkEmailExist";
$route['generate-captcha/(:any)'] = "register/generateCaptcha/$1";
$route['check-captcha'] = "register/checkCaptcha";
$route['chk-username-duplicate'] = "register/chkUserDuplicate";
$route['user-activation/(:any)'] = "register/userActivation/$1";
$route['forgot-password'] = "register/passwordRecovery";
$route['send-forgot-password-link'] = "register/forgotPasswordLink";
$route['reset-password/(:any)'] = "register/resetPassword/$1";
$route['reset-password'] = "register/resetPassword";
$route['reset-password-with-otp'] = "register/resetPasswordWithOTP";
$route['reset-valid-otp'] = "register/chkVlidOTP";
$route['get-cities-details'] = "register/cities";
$route['check-current-zipcode'] = "register/checkCurrentAddZipcode";
$route['check-permanant-zipcode'] = "register/checkPermanentAddZipcode";
$route['check-office-zipcode'] = "register/checkOfficeAddZipcode";
/*
 *  User login and registration section end 
 */


/*
 * Complete Profile 
 */

$route['complete-profile'] = "user_profile/completeProfile";


/* Testimonial Routes front */
$route['testimonial'] = "testimonial/viewTestimonial";
/* Testimonial Routs front end */


//get state and city info

$route['front/get-state-info'] = 'city/getStateInfoFront';
$route['front/get-city-info'] = 'city/getCityInfoFront';

/* Web Services Routes Starts From Here */
$route['ws-country-list'] = "web_services/countriesListWebService";
$route['ws-state-list'] = "web_services/getStateInfoWebService";
$route['ws-city-list'] = "web_services/getCityInfoWebService";
$route['ws-cms-pages'] = "web_services/cmsPageWebservice";
$route['ws-register-generate-otp'] = "web_services/generateOTP";
$route['ws-verify-otp'] = "web_services/verifyOTP";
$route['ws-complete-profile'] = "web_services/completeProfile";
$route['ws-add-address'] = "web_services/addAddresses";
$route['ws-update-device-token'] = "web_services/updateDeviceToken";
$route['ws-login'] = "web_services/login";
$route['ws-forgot-password'] = "web_services/forgotPassword";
$route['ws-profile-details'] = "web_services/profileDetails";
$route['ws-notification-details'] = "web_services/notificationDetails";
$route['ws-delete-notification'] = "web_services/deleteNotification";
$route['ws-add-contact'] = "web_services/addContact";
$route['ws-upload-profile-picture'] = "web_services/uploadProfilePicture";
$route['ws-user-address'] = "web_services/userAddress";
$route['ws-send-contact-details'] = "web_services/sendAllContactDetails";
$route['ws-get-contact-details'] = "web_services/getAllContacts";
$route['ws-update-profile'] = "web_services/updateUserDetails";
$route['ws-validate-password'] = "web_services/validatePassword";
$route['ws-contact-user-info'] = "web_services/getContactUserInformation";
$route['ws-give-address-access'] = "web_services/giveAddressAccess";
$route['ws-get-alerts'] = "web_services/alerts";
$route['ws-address-access-list'] = "web_services/viewAccessList";
$route['ws-delete-access-list'] = "web_services/deleteContactFronAccessList";
$route['ws-add-access-list'] = "web_services/addContactInAccessList";
$route['ws-add-forwording-address'] = "web_services/addForwardingAddress";
$route['ws-view-forwording-address'] = "web_services/viewForwardedAddress";
$route['ws-delete-forwording-address'] = "web_services/deleteForwardedAddress";
$route['ws-give-temprary-access'] = "web_services/giveTempraryAccess";
$route['ws-delete-temprary-access'] = "web_services/deleteTempraryAccess";
$route['ws-view-temprary-access'] = "web_services/viewTempraryAccess";
$route['ws-update-temprary-access'] = "web_services/updateTempraryAccess";
$route['ws-verify-email'] = "web_services/verifyEmail";
$route['ws-check-verify-email'] = "web_services/checkIsEmailVerify";
$route['ws-delete-office-address'] = "web_services/deleteOfficeAddress";
$route['ws-block-contact'] = "web_services/blockContact";
$route['ws-faqs'] = "web_services/faqsPageWebservice";
$route['ws-faqs/(:any)'] = "web_services/faqsPageWebservice/$1";
$route['ws-faqs-details/(:any)'] = "web_services/faqDetails/$1";
$route['ws-faqs-details/(:any)'] = "web_services/faqDetails/$1/$2";
$route['ws-faq-search-tags'] = "web_services/faqSeachTags";
$route['ws-contact-us'] = "web_services/contactUs";
$route['ws-read-notification'] = "web_services/readNotification";
$route['ws-notification-count'] = "web_services/getNotificationCount";
$route['ws-contact-block-user'] = "web_services/getContactBlockUser";
$route['crone-job-for-temp-access'] = "admin/cronJobForTempAccess";
$route['crone-job-for-delete-temp-access'] = "admin/cronJobForDeleteTempAccess";
$route['ws-country-details-iso'] = "web_services/countriesListWebServiceISO";
$route['ws-get-registered-contact'] = "web_services/getRegistredContactDetails";
$route['ws-get-address-details'] = "web_services/getAddressDetails";
$route['ws-i-am-here'] = "web_services/iAmHere";

$route['ws-near-by-users-for-live'] = "web_services/getLiveOrWorkedNearByUsers";
$route['ws-update-current-location'] = "web_services/updateCurrentLocation";
$route['ws-current-location-near-by-users'] = "web_services/getCurrentNearByUsers";
$route['ws-give-current-near-by-location-access'] = "web_services/giveCurrentNearByLocationAccess";
$route['ws-view-current-near-by-location-access-list'] = "web_services/viewCurrentLocationAccessList";
$route['ws-delete-contact-from-access-list'] = "web_services/deleteContactFromAccessList";
$route['ws-emergency-help'] = "web_services/emergncyHelp";



$route['api-demo-registration'] = "ecommerce_api_demo/regitration";
$route['api-demo-sign-in'] = "ecommerce_api_demo/signin";
$route['api-demo-profile'] = "ecommerce_api_demo/profile";
$route['api-demo-address-details'] = "ecommerce_api_demo/addressDetails";
$route['api-demo-logout'] = "ecommerce_api_demo/logout";