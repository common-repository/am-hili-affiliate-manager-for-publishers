<?php
// If this file is called directly, exit.
if (!defined( 'ABSPATH' )) { exit(); }

if (!is_user_logged_in() or !isset($_POST['_am_hili_'])){return;};

if (!wp_verify_nonce( $_POST['_am_hili_'],'am_hili_nonce')){return;};

// saving default setting as json encoded
update_am_hili_options('am_hili_options', json_encode($_POST['am_hili'],JSON_UNESCAPED_UNICODE));

wp_safe_redirect(AM_HILI_ADMIN_URL);
exit();