    <?php
    	//file
        $file = '/var/www/whatsappSolution/public/uploads/media/4_Media_photo987f3a13.jpg';
        $text = rawurlencode('hello');

    	if (function_exists('curl_file_create')) {
    		$curlFile = curl_file_create($file);
    	} else {
    		$curlFile = '@' . realpath($file);
    	}


            $curl = curl_init();

            // Send the POST request with cURL
            curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://reseller.whatsapp.solution/api/send",
            CURLOPT_POST => 1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            //CURLOPT_HTTPHEADER => array('X-Authentication-Key:API-KEY', 'X-Api-Method:MT'),
            CURLOPT_POSTFIELDS => array(
			                      'campaign' => 'API-CAMPAIGN',
                            'mobile' => '1234567890,0987654321',
                            'key' => '1234567890',
                            'file' => $curlFile,
                            'type' => 'image',
                            'message' => $text)));

        // Send the request & save response to $response
        $response = curl_exec($curl);

        // Close request to clear up some resources
        curl_close($curl);

        // Print response
        print_r($response);
    ?>
