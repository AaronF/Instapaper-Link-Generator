<?php
require 'src/instapaper_xauth.php';
require 'config.php';

if(!$_GET["folder_id"]){
    die("No folder ID set");
} else {
    $insta = new Instapaper_XAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $oauth_cred = $insta->login(INSTA_USERNAME, INSTA_PASSWORD);

    $oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $oauth->setToken($oauth_cred["oauth_token"], $oauth_cred["oauth_token_secret"]);
    $oauth->fetch($api_url.'account/verify_credentials');

    $verify_cred_response = json_decode($oauth->getLastResponse(), true)[0];
    if($verify_cred_response["user_id"]){
        $oauth->fetch($api_url.'bookmarks/list', array("folder_id" => $_GET["folder_id"]));

        $list_bookmarks_response = json_decode($oauth->getLastResponse(), true);
    }
}
?>
<html>
    <head>
    </head>
    <body>
        <strong>Output</strong>
        <textarea style="width: 100%;" rows="25"><?php
        echo "<ul>\n";
        foreach($list_bookmarks_response as $bookmark){
            if($bookmark["type"] == "bookmark"){
                echo "<li>".$bookmark["title"]." [<a href='".$bookmark["url"]."' target='_blank'>Link</a>]</li>\n";
            }
        }
        echo "</ul>";
        ?>
        </textarea>
    </body>
</html>
