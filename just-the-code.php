//First cURL - Get auth code
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=refresh_token&refresh_token=REFRESH_TOKEN_HERE");

		$headers = array();
		$headers[] = 'Authorization: Basic DEVELOPER_APP_AUTH_HERE';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);	
		//Decode JSON
		$codedata = json_decode($result,true);
		
		//Second cURL - Use refreshed auth code to get current track 		
		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/me/player/currently-playing?market=MARKET_HERE');
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    	$headers = array();
    	$headers[] = 'Accept: application/json';
   		$headers[] = 'Content-Type: application/json';
    	$headers[] = 'Authorization: Bearer ' . $codedata["access_token"];
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    	$result = curl_exec($ch);
    	if (curl_errno($ch)) {
        	echo 'Error:' . curl_error($ch);
    	}
    	curl_close($ch);
		//Decode JSON
    	$data = json_decode($result,true);
    
    	echo '<div style="width:270px; height:80px; background: rgb(54,164,192); background: rgb(129,186,141); background: linear-gradient(180deg, rgba(129,186,141,1) 0%, rgba(53,112,50,1) 100%); padding:8px;">';
			if ($data["item"]["name"] == '') { 
				echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" aria-labelledby="title" aria-describedby="desc" role="img" xmlns:xlink="http://www.w3.org/1999/xlink" style="float:left;margin-right:10px;max-height:65px;">
					<title>Pause</title>
					<desc>A line styled pause button icon</desc>
					<circle data-name="layer2"
					cx="32" cy="32" r="30" fill="none" stroke="#fff" stroke-miterlimit="10"
					stroke-width="2" stroke-linejoin="round" stroke-linecap="round"></circle>
					<path data-name="layer1" fill="none" stroke="#fff" stroke-miterlimit="10"
					stroke-width="2" d="M36 20h4v24h-4zm-12 0h4v24h-4z" stroke-linejoin="round"
					stroke-linecap="round"></path>
				</svg>'; echo '<b>Paused</b>'; }
        	echo '<img style="float:left;margin-right:10px;max-height:65px;" src="'.$data["item"]["album"]["images"][0]["url"].'">';
        	echo '<p style="color:#fff;font-family:sans-serif;line-height:1.4;"title="'.$data["item"]["name"].'"><b><a target="_blank" href="'.$data["item"]["external_urls"]["spotify"].'">'.$data["item"]["name"].'</a></b><br>';
            	foreach ($data["item"]["artists"] as $artist) {
					if (!next($data["item"]["artists"])) {
						echo $artist["name"];
					} else {
						echo $artist["name"].', ';
					}
				}
        	echo '</p>';
    	echo '</div>';
