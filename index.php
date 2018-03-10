<?php
require 'src/instapaper_xauth.php';
require 'config.php';

$insta = new Instapaper_XAuth(CONSUMER_KEY, CONSUMER_SECRET);
$oauth_cred = $insta->login(INSTA_USERNAME, INSTA_PASSWORD);

$oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
$oauth->setToken($oauth_cred["oauth_token"], $oauth_cred["oauth_token_secret"]);
$oauth->fetch($api_url.'account/verify_credentials');

$verify_cred_response = json_decode($oauth->getLastResponse(), true)[0];
if($verify_cred_response["user_id"]){
    $oauth->fetch($api_url.'folders/list');

    $list_folders_response = json_decode($oauth->getLastResponse(), true);
}

?>
<html>
    <head></head>
    <body>
        <strong>Folder List</strong>
        <ul>
            <?php
            foreach($list_folders_response as $folder){
                echo "<li>Title: ".$folder["title"]." <br>Folder ID: ".$folder["folder_id"]."<br><br></li>";
            }
            ?>
        </ul>

        <strong>Generate Links</strong>
        <form action="generate_links.php" method="GET">
            <input type="text" name="folder_id" placeholder="Folder ID"/>
            <input type="submit" value="Generate"/>
        </form>
    </body>
</html>
