<?php
require_once 'config.php';
 
function list_meetings($next_page_token = '') {
    $db = new DB();
    $arr_token = $db->get_access_token();
    $accessToken = $arr_token->access_token;
 
    $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
   
    $arr_request = [
        "headers" => [
            "Authorization" => "Bearer $accessToken"
        ]
    ];
  
    if (!empty($next_page_token)) {
        $arr_request['query'] = ["next_page_token" => $next_page_token];
    }
  
    $response = $client->request('GET', '/v2/users/me/meetings', $arr_request);
      
    $data = json_decode($response->getBody());
  
    if ( !empty($data) ) {
        foreach ( $data->meetings as $d ) {
            $topic = $d->topic;
            $join_url = $d->join_url;
            echo "<h3>Topic: $topic</h3>";
            echo "Join URL: $join_url";
        }
  
        if ( !empty($data->next_page_token) ) {
            list_meetings($data->next_page_token);
        }
    }
}
  
list_meetings();