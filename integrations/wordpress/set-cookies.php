<?php
namespace Segment\Integrations\Wordpress;
use Segment\Core\Cookie;

function set_user_login_cookie( $login, $user ){

    Cookie\set_cookie( 'logged_in', md5( json_encode( $user ) ) );

}


function set_user_registration_cookie(  $user_id ){

    Cookie\set_cookie( 'signed_up', json_encode( $user_id ) );

}