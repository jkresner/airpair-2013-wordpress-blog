<?php
/*
Extension Name: Wordbooker
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Interface calls for using curl.
Author: Steve Atty
*/
function wordbooker_fb_stream_publish($data,$target) {
	$url='https://graph.facebook.com/'.$target.'/feed';
	$x=wordbooker_make_curl_post_call($url,$data);
	 return($x);
}

function wordbooker_fb_action_publish($data,$target) {
	$url='https://graph.facebook.com/'.$target.'/news.publishes';
	$data['fb:explicitly_shared'] = 'true';
	$data['article'] = $data['link'];
	//var_dump($data);
	$x=wordbooker_make_curl_post_call($url,$data);
	 return($x);
}
function wordbooker_fb_status_update($data,$target) {
	$url='https://graph.facebook.com/'.$target.'/feed';
	$x=wordbooker_make_curl_post_call($url,$data);
    return($x);
}
function wordbooker_fb_link_publish($data,$target) {
	$url='https://graph.facebook.com/'.$target.'/links';
	$x=wordbooker_make_curl_post_call($url,$data);
    return($x);
}
function wordbooker_fb_note_publish($data,$target){
	$url='https://graph.facebook.com/'.$target.'/notes';
	$x=wordbooker_make_curl_post_call($url,$data);
    return($x);
}
function wordbooker_fql_query($query,$access_token) {
	$url = 'https://api.facebook.com/method/fql.query?&query='.rawurlencode($query).'&format=JSON-STRINGS&access_token='.$access_token;
	$x=wordbooker_make_curl_call($url);
    return($x);
}
function wordbooker_me($access_token) {
    $url = 'https://graph.facebook.com/me/accounts?access_token='.$access_token.'&format=JSON';
	$x=wordbooker_make_curl_call($url);
    return($x);
}

function wordbooker_me_groups($access_token) {
    $url = 'https://graph.facebook.com/me/groups?access_token='.$access_token.'&format=JSON';
	$x=wordbooker_make_curl_call($url);
    return($x);
}

function wordbooker_get_fb_id($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
    $url = 'https://graph.facebook.com/'.$fb_id.'?fields=id,name,link&access_token='.$access_token.'&format=JSON-STRINGS';
	$x=wordbooker_make_curl_call($url);
    return($x);
}
function wordbooker_me_status($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
    $url = 'https://graph.facebook.com/'.$fb_id.'?access_token='.$access_token.'&format=JSON';
	$x=wordbooker_make_curl_call($url);
    return($x);
}

function wordbooker_friend_lists($fb_id,$access_token) {
     $url = 'https://graph.facebook.com/'.$fb_id.'?fields=friendlists.limit(1000)&access_token='.$access_token.'&format=JSON';
	$x=wordbooker_make_curl_call($url);
    return($x);
}

function wordbooker_friends($access_token,$flid) {
     $url = 'https://graph.facebook.com/'.$flid.'/members?access_token='.$access_token.'&format=JSON';
     if ($flid==-100) {
		$url = 'https://graph.facebook.com/me/friends?access_token='.$access_token.'&format=JSON';
    }
	$x=wordbooker_make_curl_call($url);
    return($x);
}
function wordbooker_delete_fb_post($fb_post_id,$access_token){
	$url='https://graph.facebook.com/'.$fb_post_id.'?method=delete&access_token='.$access_token;
	$x=wordbooker_make_curl_call($url);
    return($x);
}

function wordbooker_get_access_token($access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$secret='df04f22f3239fb75bf787f440e726f31'; } else {$secret=WORDBOOKER_FB_SECRET;}
    $url='https://graph.facebook.com/oauth/access_token?client_id='.WORDBOOKER_FB_ID.'&client_secret='.$secret.'&grant_type=fb_exchange_token&fb_exchange_token='.$access_token;
    $x=wordbooker_make_curl_call2($url);
	//wordbooker_debugger("Access token returns ",$x,-5,98) ;
	return($x);
}

function wordbooker_get_access_token_from_code($code) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$secret='df04f22f3239fb75bf787f440e726f31'; } else {$secret=WORDBOOKER_FB_SECRET;}
    $url='https://graph.facebook.com/oauth/access_token?client_id='.WORDBOOKER_FB_ID.'&client_secret='.$secret.'&code='.$code.'&redirect_uri='.urlencode(get_bloginfo('wpurl')).'/wp-admin/options-general.php?page=wordbooker';
	$x=wordbooker_make_http_call2($url);
   // wordbooker_debugger("Access token returns ",$x,-5,98) ;
	return($x);
}

function wordbooker_check_access_token($access_token) {
	if (!defined('WORDBOOKER_FB_ACCESS_TOKEN')) {$access='254577506873|szBVgLKb2hvtvSkMeSMTkaPnGFM'; } else {$access=WORDBOOKER_FB_ACCESS_TOKEN;}
	 $url='https://graph.facebook.com/debug_token?input_token='.$access_token.'&access_token='.$access;
	 try {
	$x=wordbooker_make_curl_call2($url);
	}
	catch (Exception $e) {
		$x = $e;
	}
	return($x);
}

function wordbooker_check_version() {
	$version=explode(" ",WORDBOOKER_CODE_RELEASE);
	$url='https://wordbooker.tty.org.uk/check_ver.cgi?ver='.urlencode($version[0])."&blog=".urlencode(network_site_url());
	$x=wordbooker_make_curl_call2($url);
	return($x);
}

function wordbooker_status_feed($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
    $url = 'https://graph.facebook.com/'.$fb_id.'/feed/?access_token='.$access_token.'&format=JSON&limit=10';
	$x=wordbooker_make_curl_call($url);
    return($x);
}
function wordbooker_fb_pemissions($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
    $url = 'https://graph.facebook.com/'.$fb_id.'/permissions?access_token='.$access_token.'&format=JSON';
	$x=wordbooker_make_curl_call($url);
    return($x);
}
function wordbooker_fb_get_comments($fb_id,$access_token) {
    $url = 'https://graph.facebook.com/'.$fb_id.'/comments?access_token='.$access_token;
	$x=wordbooker_make_curl_call($url);
    return($x);
}

function wordbooker_fb_get_box_comments($url) {
  	$url = 'https://graph.facebook.com/comments?ids='.$url;
	$x=wordbooker_make_curl_call($url);
    return($x);
}
function wordbooker_fb_put_comments($fb_id,$comment,$access_token) {
    $url = 'https://graph.facebook.com/'.$fb_id.'/comments';
	$data['message']=$comment;
	$data['access_token']=$access_token;
	$x=wordbooker_make_curl_post_call($url,$data);
    return($x);
}

function wordbooker_fb_create_event($fb_id,$event_data,$access_token) {
    $url = 'https://graph.facebook.com/'.$fb_id.'?access_token='.$access_token;
	$event_data = array(
	    'name'          => 'Event: ' . date("H:m:s"),
	    'start_time'    => time() + 60*60,
	    'end_time'      => time() + 60*60*2,
	    'owner'         => $page
	);
	$x=wordbooker_make_curl_post_call($url,$data);
    return($x);
}

function wordbooker_make_curl_call($url) {
	global $wordbooker_settings;
 	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch,CURLOPT_USERAGENT,WORDBOOKER_USER_AGENT);
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/fb_ca_chain_bundle.crt');
	if (WORDBOOKER_IPV==6 && isset($wordbooker_settings['wordbooker_use_curl_4'])) {
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	}
    $response = curl_exec($ch);
	$err_no=curl_errno($ch);
	$err_text=curl_error($ch);
    curl_close($ch);
	$x=json_decode( $response);
	if (isset($x->error_msg)) {
	$error=$x->error_msg;}
	if (isset($x->error->message)) {
	$error=$x->error->message;}
	if (isset($error)) {
		throw new Exception ($error);
	}
	return( $x);
}

function wordbooker_make_curl_call2($url) {
	global $wordbooker_settings;
 	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch,CURLOPT_USERAGENT,WORDBOOKER_USER_AGENT);
	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/fb_ca_chain_bundle.crt');
	if (WORDBOOKER_IPV==6 && isset($wordbooker_settings['wordbooker_use_curl_4'])) {
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	}
    $response = curl_exec($ch);
	$err_no=curl_errno($ch);
	$err_text=curl_error($ch);
    curl_close($ch);
	//wordbooker_debugger("Curl Call returns ",print_r($response,true),-5,98) ;
	$x=json_decode($response);
	if (is_null($x)) {$x=$response;}
	if (isset($x->error->message)) {
		throw new Exception ($x->error->message);
	}
	 return( $x);
}

function wordbooker_make_curl_post_call($url,$data) {
	global $wordbooker_settings;
 	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch,CURLOPT_USERAGENT,WORDBOOKER_USER_AGENT);
   	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/fb_ca_chain_bundle.crt');
   	if (WORDBOOKER_IPV==6 && isset($wordbooker_settings['wordbooker_use_curl_4'])) {
   	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
   	}
   	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
	$err_no=curl_errno($ch);
    curl_close($ch);
	$x=json_decode($response);
	if (isset($x->error->message)) {
		throw new Exception ($x->error->message);
	}
	 return($x);
}
?>