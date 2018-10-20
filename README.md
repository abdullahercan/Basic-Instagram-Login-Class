# Basic Instagram Login Class

## Get started
To use the API, register as an developer in [Instagram Developer](https://www.instagram.com/developer/), create an application and receive `Client ID` `Client Secret` keys.

Manage Clients> Register a New Clients follow this path

## Initialize
    $instagram = new instagram([  
      "key" => "API_KEY",  
      "secret" => "API_SECRET",  
      "callback" => "CALLBACK_URL"  
    ]);
    echo '<a href="'.$instagram->loginUrl().'">Login with Instagram</a>';

## Authenticate
    $token  = $_GET["token"];
    $instagram->setAccessToken($token);
    
## Get user data
    $user_data  = $instagram->getUser();
    echo '<pre>';
    print_r($user_data);
    echo '<pre>';
