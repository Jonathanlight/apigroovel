## Groovel api rest ##

The rest/api is built in via dingo/api and the security is managed via tymon/jwt-auth

You have to add the service provider and alias to config/app.php :

###INSTALL THE PROVIDER DINGO/API##

in order to install Dingo/API add the provider:
 
Dingo\Api\Provider\LaravelServiceProvider::class,

###PUBLISH THE PROVIDER DINGO/API##

Then run the command :

 php artisan vendor:publish provider="Dingo\Api\Provider\LaravelServiceProvider"
 In config/app.php file you should see a new file api.php
 
 locate the line  'version' => env('API_VERSION', 'v1'), and set the version to v1
 
 in .env you have to add the lines: 
API_PREFIX=api
API_VERSION=v1

###INSTALL THE PROVIDER JWT##

You have to add the service provider and alias to config/app.php :

Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,

and

'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
  

###PUBLISH THE PROVIDER JWT##

php artisan vendor:publish provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
 
###Configure Dingo/Api####

Edit config/App.php and locate the line 'auth' and add the jwt provider:
 'auth' => [
    		'jwt' => 'Dingo\Api\Auth\Provider\JWT'
    ],

###Generate the key for jwt ######

run the command :

php artisan jwt:generate
 
###Finish to add the provider to connect groovel to api####

in config/app.php add the provider:
 
Groovel\Restapi\APIServiceProvider::class,

Finish!
