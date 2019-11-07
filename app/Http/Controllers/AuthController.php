<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \League\OAuth2\Client\Provider\GenericProvider;
use App\TokenStore\TokenCache;

class AuthController extends Controller
{
    public function signin()
    {
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
  
      // Initialize the OAuth client
      $oauthClient = new GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => env('OAUTH_SCOPES')
      ]);
  
      // Generate the auth URL
        $authorizationUrl = $oauthClient->getAuthorizationUrl();

        // Save client state so we can validate in response
        $_SESSION['oauth_state'] = $oauthClient->getState();

        // Redirect to authorization endpoint
        header('Location: '.$authorizationUrl);
        exit();
    }



    public function gettoken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
          }
        
          // Authorization code should be in the "code" query param
          if (isset($_GET['code'])) {
            // Check that state matches
            if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth_state'])) {
              exit('State provided in redirect does not match expected value.');
            }
        
            // Clear saved state
            unset($_SESSION['oauth_state']);
        
            // Initialize the OAuth client
            $oauthClient = new GenericProvider([
              'clientId'                => env('OAUTH_APP_ID'),
              'clientSecret'            => env('OAUTH_APP_PASSWORD'),
              'redirectUri'             => env('OAUTH_REDIRECT_URI'),
              'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
              'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
              'urlResourceOwnerDetails' => '',
              'scopes'                  => env('OAUTH_SCOPES')
            ]);
        
            try {
              // Make the token request
              $accessToken = $oauthClient->getAccessToken('authorization_code', [
                'code' => $_GET['code']
              ]);
        
              $tokenCache = new TokenCache;
              $tokenCache->storeTokens($accessToken->getToken(), $accessToken->getRefreshToken(),
                $accessToken->getExpires());
            }
            catch (IdentityProviderException $e) {
              exit('ERROR getting tokens: '.$e->getMessage());
            }
            exit();
          }
          elseif (isset($_GET['error'])) {
            exit('ERROR: '.$_GET['error'].' - '.$_GET['error_description']);
          }
    }

    public function signin2()
    {
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
  
      // Initialize the OAuth client
      $oauthClient = new GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => env('OAUTH_SCOPES')
      ]);
  
      // Generate the auth URL
        $authorizationUrl = $oauthClient->getAccessToken('client_credentials');
      

        // Save client state so we can validate in response
        $_SESSION['oauth_state'] = $oauthClient->getState();

        // Redirect to authorization endpoint
        header('Location: '.$authorizationUrl);
        exit();
    }
}
