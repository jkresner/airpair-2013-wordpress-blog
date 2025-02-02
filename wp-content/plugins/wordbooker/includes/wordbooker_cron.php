<?php

/**
Extension Name: Wordbooker Cron
Extension URI: http://blogs.canalplan.org.uk/steve
Version: 2.2
Description: Collection of processes that are often handled by wp_cron scheduled jobs
Author: Steve Atty
*/
function wordbooker_cache_refresh($user_id) {
	global $blog_id,$wpdb,$table_prefix,$wordbooker_user_settings_id,$wbooker_user_id;
	$wbooker_user_id=$user_id;
	$result = $wpdb->query(' DELETE FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE   blog_id ='.$blog_id.' and (user_ID='.$user_id.' or user_ID=0 ) and post_id<=1 and post_id>-3');
	wordbooker_debugger("Cache Refresh Commence ",$user_id,-1,9) ;
	$result = $wpdb->get_row("select facebook_id from ".WORDBOOKER_USERDATA." where user_ID=".$user_id);
	$uid=$result->facebook_id;
	$wbuser2= wordbooker_get_userdata($user_id);
	$wordbooker_settings =get_option('wordbooker_settings');
	$doy=date ( 'z');
	$curr_version="";
	$version_check=wordbooker_get_option('version_check');
	if($doy!=$version_check) {
		 $curr_version=wordbooker_check_version();
	     wordbooker_set_option('version_check', $doy );
	     if (strlen($curr_version)>10){$curr_version='0.0.0';}
	     if (strlen($curr_version)<4){$curr_version='0.0.0';}
		 wordbooker_set_option('current_release', $curr_version );
	}
	wordbooker_debugger("Cache Refresh for ",$wbuser2->name,-1,90) ;
	wordbooker_debugger("UID length : ",strlen($uid),-1,9) ;
	# If we've not got the ID from the table lets try to get it from the logged in user
	if (strlen($uid)==0) {
		wordbooker_debugger("No Cache record for user - getting Logged in user ",$uid,-1,9) ;
		try {
			$x=wordbooker_get_fb_id(null,$wbuser2->access_token);
			$uid=$x->id;
		}
		catch (Exception $e) {
			$error_code = $e->getCode();
			$error_msg = $e->getMessage();
			wordbooker_debugger($error_msg," ",-1,9) ;
		}
	}
	# If we now have a uid lets go and do a few things.
	if (strlen($uid)>0){
		wordbooker_debugger("Cache processing for user : ",$wbuser2->name." (".$uid.")",-1,90) ;
		$at=wordbooker_check_access_token($wbuser2->access_token);
		if(!$at->data->is_valid) {
			wordbooker_debugger("Cache Refresh Failed : Access Token is not valid ",$at->data->error->message,-1,9) ;
			wordbooker_debugger("Cache Refresh Failed : Access Token was ",$wbuser2->access_token,-1,9) ;
			return;
		}
		wordbooker_debugger("Getting Permisions for : ",$uid,-1,9) ;
		try {
		  $ret=wordbooker_fb_pemissions($wbuser2->facebook_id,$wbuser2->access_token);
		}
		# If we have an  $ret->error->message then we have a problem
		catch (Exception $e) {
		  $error_code = $e->getCode();
		  $error_msg = $e->getMessage();
		  wordbooker_append_to_errorlogs("Unable to get information", "99", $error_msg,'',$user_id);
		  return;
		}
		if(strlen(serialize($ret))<20) {wordbooker_debugger("Permissions fetch failed - skipping ",'',-1,9) ;} else {
		$add_auths=0;
		$permlist= array(WORDBOOKER_FB_PUBLISH_STREAM,WORDBOOKER_FB_STATUS_UPDATE,WORDBOOKER_FB_READ_STREAM,WORDBOOKER_FB_CREATE_NOTE,WORDBOOKER_FB_PHOTO_UPLOAD,WORDBOOKER_FB_VIDEO_UPLOAD,WORDBOOKER_FB_MANAGE_PAGES,WORDBOOKER_FB_READ_FRIENDS);
		$key=0;
		foreach($permlist as $perm){
		try {
			$permy=$ret->data[0]->$perm;
			$error_code = null;

			if($permy!=1) {
				wordbooker_debugger("User is missing permssion : ",$perm,-1,9) ;
				$add_auths = $add_auths | pow(2,$key);
			}
			else {
				wordbooker_debugger("User has permssion : ",$perm,-1,9) ;
			}
			$error_msg = null;
		} catch (Exception $e) {
			$error_msg = $e->getMessage();
			wordbooker_debugger("Permissions may be corrupted  ",$error_message,-1,9);
			$users = null;
			$add_auths=1;
		}
			$key=$key+1;
		}

		wordbooker_debugger("Additional Permissions needed : ",$add_auths,-1,9) ;
		$sql="update ".WORDBOOKER_USERDATA." set auths_needed=".$add_auths." where user_ID=".$user_id;
		$result = $wpdb->get_results($sql);
		}
		# Lets get the person/page this user wants to get the status for. We get this from the user_meta
		$wordbooker_user_settings_id="wordbookuser".$blog_id;
		$wordbookuser_setting=get_user_meta($user_id,$wordbooker_user_settings_id,true);
		$suid="PW:".$uid;
		if ( isset ($wordbookuser_setting['wordbooker_status_id']) && $wordbookuser_setting['wordbooker_status_id']!=-100) {$suid=$wordbookuser_setting['wordbooker_status_id'];}
		$x=explode(":",$suid);
		$suid=$x[1];

		wordbooker_debugger("Getting Pages administered by : ",$uid,-1,9) ;
		$all_pages=array();
		try {
		$ret_code=wordbooker_me($wbuser2->access_token);
		}
		catch (Exception $e)
		{
		$error_msg = $e->getMessage();
		wordbooker_debugger("Failed to get page tokens : ".$error_msg," ",-1,9);
		}
		if (isset($ret_code->data)){
		foreach($ret_code->data as $page_access) {
			$pages["access_token"]=$page_access->access_token;
			$pages["id"]="FW:".$page_access->id;
			if (function_exists('mb_convert_encoding')) {
					$pages["name"]=mb_convert_encoding($page_access->name,'UTF-8');
			}
				else
			{
					$pages["name"]=$page_access->name;
				}
			wordbooker_debugger("Page info for page ID ".$page_access->id,$pages["name"],-1,9) ;
			$all_pages[]=$pages;
		}
	}
		 else {
			wordbooker_debugger("Failed to get page information from FB"," ",-1,9);
		 }
	//	}
		$fb_group_list=array();
		$all_groups=array();
		wordbooker_debugger("Getting Groups owned or managed by : ",$uid,-1,9) ;
		try {
			// Put a call in to wordbooker_me_groups here and parse that
			//  if the administrator field is set then we should store - otherwise not.
			$fb_groups= wordbooker_me_groups($wbuser2->access_token);
			if(is_array($fb_groups->data)){
				foreach($fb_groups->data as $fb_group){
					# Check to see if there are any positions. If not then the user is only a member of the group and thus we dont want it in the list.
					if(isset($fb_group->administrator)) {
						wordbooker_debugger("Getting details for group : ",$fb_group->id,-1,9) ;
					    $fb_group_list[] = new stdClass();
						$fb_group_list[]->name=$fb_group->name;
						if (function_exists('mb_convert_encoding')) {
							$groups["name"]=mb_convert_encoding($fb_group->name,'UTF-8');
						}
						else
						{
							$groups["name"]=$fb_group->name;
						}
						$groups["page_id"] = new stdClass();
						$groups["page_id"]->gid=$fb_group->id;
						$groups["id"]="GW:".$fb_group->id;
						$groups["access_token"]="dummy access token";
						$all_groups[]=$groups;
						wordbooker_debugger("Group info for group ID ".$fb_group->id,$fb_group->name,-1,9) ;
					}
				}
			}
		}

		catch (Exception $e)
		{
			$error_msg = $e->getMessage();
			wordbooker_debugger("Failed to get group info : ",$error_msg,-1,9);
		}

		$all_pages_groups=@array_merge($all_pages,$all_groups);
		$encoded_names=str_replace('\\','\\\\',serialize($all_pages_groups));

		$fb_status_info=wordbooker_status_feed($suid,$wbuser2->access_token);
		if (!is_null($fb_status_info)) {
			foreach($fb_status_info->data as $fbstat) {
				if(!is_null($fbstat->message)){
					if (($suid==$fbstat->from->id) && (!isset($fbstat->to->data[0]->id ) )) {
						$status_message=$fbstat->message;
						$status_time=$fbstat->created_time;
						break;
					}
				 }
			}
		}
		$picture = 'https://graph.facebook.com/'.$suid.'/picture?type=normal';
		$fb_profile_info=wordbooker_get_fb_id($suid,$wbuser2->access_token);
		wordbooker_debugger("Setting Status Name as  : ",$fb_profile_info->name,-1,9) ;
		$sql="insert into ".WORDBOOKER_USERSTATUS." set name='".$wpdb->escape($fb_profile_info->name)."'";
		if (isset($status_time)) {
			if (stristr($status_message,"[[PV]]")) {
				wordbooker_debugger("Found [[PV]] - not updating status"," ",-1,9);
			}
			else {
				wordbooker_debugger("Setting status as  : ",$wpdb->escape($status_message),-1,9) ;
				$sql.=", status='".$wpdb->escape($status_message)."'";
				$sql.=", updated=".$wpdb->escape(strtotime($status_time));
			}
		}
		else {
			wordbooker_debugger("Failed to get Status information from FB"," ",-1,9);
		}

		wordbooker_debugger("Setting Status URL as  : ",$wpdb->escape($fb_profile_info->link),-1,9) ;
		$sql.=", url='".$wpdb->escape($fb_profile_info->link)."'";
		$sql.=", pic='".$wpdb->escape($picture)."'";
		$sql.=", facebook_id='".$uid."'";
		$sql.=",user_ID=".$user_id;
		$sql.=",blog_id=".$blog_id;
		$sql.=" on duplicate key update name='".$wpdb->escape($fb_profile_info->name)."'";
		if (isset($status_message)) {
			if (stristr($status_message,"[[PV]]")) {

			}
			else {
				$sql.=", status='".$wpdb->escape($status_message)."'";
				$sql.=", updated=".$wpdb->escape(strtotime($status_time));
			}
		}
		if (isset($fb_profile_info->link)) {
			$sql.=", url='".$wpdb->escape($fb_profile_info->link)."'";
			$sql.=", pic='".$wpdb->escape($picture)."'";
		}
		$result = $wpdb->get_results($sql);
		$real_user=wordbooker_get_fb_id($uid,$wbuser2->access_token);
		wordbooker_debugger("Setting user name as  : ",$wpdb->escape($real_user->name),-1,9) ;
		$sql="update ".WORDBOOKER_USERDATA." set name='".$wpdb->escape($real_user->name)."'";

		$sql.=", facebook_id='".$uid."'";
		$sql.=", pages= '".$wpdb->escape($encoded_names)."'";
		$sql.=", use_facebook=1";
		$sql.="  where user_ID=".$user_id." and blog_id=".$blog_id;

		$result = $wpdb->get_results($sql);
	}
	wordbooker_debugger("Cache Refresh Complete for user",$uid,-1,90) ;
}

function wordbooker_poll_facebook($single_user=null) {
	global  $wpdb, $user_ID,$table_prefix,$blog_id;
	# If a user ID has been passed in then restrict to that single user.
	wordbooker_trim_errorlogs();
	$limit_user="";
	if (isset($single_user)) {$limit_user=" where user_id=".$single_user." limit 1";}
	$wordbooker_settings =get_option('wordbooker_settings');

	# This runs through the Cached users and refreshes them
      	$sql="Select user_id,name from ".WORDBOOKER_USERDATA.$limit_user;
        $wb_users = $wpdb->get_results($sql);
	if (is_array($wb_users)) {
		wordbooker_debugger("Batch Cache Refresh Commence "," ",-1,00) ;
		foreach ($wb_users as $wb_user){
			wordbooker_debugger("Calling Cache refresh for  :  ",$wb_user->name." (".$wb_user->user_id.")",-1,90) ;
			$wbuser = wordbooker_get_userdata($wb_user->user_id);
		#	$fbclient = wordbooker_fbclient($wbuser);
			wordbooker_cache_refresh($wb_user->user_id);
		}
		wordbooker_debugger("Batch Cache Refresh completed "," ",-1,90) ;
	}
}
?>