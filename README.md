# spotify-now-playing
Add a widget to a Wordpress website to show visitors what you're listening to on Spotify.
This is designed to work in Wordpress primarily using PHP.
If you want to add a WordPress widget to show Now Playing, use the included functions.php file and copy that code to your theme's functions.php.
If you just want the PHP, use just-the-code.php incuded in this repository.
Shows the currently playing song name, artist(s), album art, and includes a link to the song.

![Screenshot](https://github.com/rohangardiner/spotify-now-playing/blob/main/spotify-widget.PNG?raw=true)

# You will need:
-A Spotify account (Free or Premium);
-CPanel or FTP access to your website.

# How to use
1. Go to https://developer.spotify.com/dashboard and log in. Use the Spotify account you want to use to display the song playing
2. Click the 'Create an App' button, give it a name and description. These will not be public and don't make a difference to the code.
3. Copy the Client ID and Client Secret and save them somewhere you'll remember.
4. Open a web browser and type this into the address bar: https://accounts.spotify.com/authorize?response_type=code&client_id=CLIENT_ID&scope=user-read-currently-playing&redirect_uri=http://localhost/
5. Replace CLIENT_ID in the above address with your Developer App Client ID and press enter, you will get an error page.
6. When the error page loads, check the URL and copy down the CODE, it will be in the form: http://localhost/?code=CODE_HERE
7. Now that you have the Client ID, Client secret, and Special Code, we can get your Refresh Token - The final puzzle piece.
8. You'll need to run a cURL command, to do this create a new file on your web server, name it anything (eg spotify.php) and copy the below code. Replace CLIENT_ID, CLIENT_SECRET, and CODE in the php with your unique tokens. Navigate to the file in a web browser to run the command.

```
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=CLIENT_ID&client_secret=CLIENT_SECRET&grant_type=authorization_code&code=CODE&redirect_uri=http://localhost/");
$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
var_dump(json_decode($result,true));
curl_close($ch);
```

9. Once run, you will get a dump of JSON code, look for the value next to `["refresh_token"]=>` it will be a long string of characters.
10. Now we need to encode the Client ID and Client Secret. Go to https://www.base64encode.org/ and enter CLIENT_ID:CLIENT_SECRET to be encoded. (eg if your Client ID was abcde12345 and your Client Secret was uvwxyz67890, you would enter abcde12345:uvwxyz67890
11. Copy the output, it will be a long string of encoded characters.
12. Now you can copy the code from the other files in this repository - functions.php or just-the-code.php into your Wordpress functions or as PHP code.
13. Replace `REFRESH_TOKEN_HERE` with your refresh token from Step 9.
14. Replace `DEVELOPER_APP_AUTH_HERE` with your encoded ClientID:ClientSecret from Step 11.
15. Replace `MARKET_HERE` with your Spotify market location, i.e. your country code (Use this page to get available market codes: https://developer.spotify.com/console/get-available-markets/)
Using the same account as your Developer App, play a song through Spotify. When not playing, the widget diverts to a Paused icon.
