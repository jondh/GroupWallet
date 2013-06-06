<?php

require "facebook.php";
$config['appId'] = '315826831884119';
$config['secret'] = '3544fcde9e698c45a35abd597a3409e1';
$config['fileUpload'] = false;

$facebook = new Facebook($config);


$user = $facebook->getUser();
if ($user) 
{
try 
{
// Proceed knowing you have a logged in user who's authenticated.
$user_profile = $facebook->api("$user");
} 
catch (FacebookApiException $e) 
{
error_log($e);
$user = null;
}
}
// Login or logout url will be needed depending on current user state.
if ($user) {
$logoutUrl = $facebook->getLogoutUrl();
} else {
$loginUrl = $facebook->getLoginUrl();
}
$app_id = "315826831884119";
$canvas_page = "https://apps.facebook.com/group_wallet/";

$auth_url = "https://www.facebook.com/dialog/oauth?client_id=" 
. $app_id . "&redirect_uri=" . urlencode($canvas_page);
$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
if (empty($data["user_id"])) {
echo("<script> top.location.href='" . $auth_url . "'</script>");
} else {
//echo ("Welcome User: " . $data["user_id"]);
echo ("Welcome " .$user_profile['name']);
}
$feed_url = "https://www.facebook.com/dialog/feed?app_id=" 
. $app_id . "&redirect_uri=" . urlencode($canvas_page)
. "&message=" . $message;
if (empty($_REQUEST["post_id"])) 
{
echo("<script> top.location.href='" . $feed_url . "'</script>");
}
else 
{
echo ("Feed Post Id: " . $_REQUEST["post_id"]);
}
?>