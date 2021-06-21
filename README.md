# # Allegro SDK for PHP
### Work in progress...

**Allegro SDK for PHP** is a package with an elegant syntax for connecting to the Allegro API

More information coming soon.

|Description |Package method name|Allegro documentation link|
|------------|-------------------|--------------------------|
|Get IDs of Allegro categories | getAllCategories | [Link](https://developer.allegro.pl/documentation/#operation/getCategoriesUsingGET) |
|... | ... | ... |

## Authentication

    use Olekjs\Allegro\Authentication\CodeFlow\Auth;
    
    $clientId     = '';
    $clientSecret = '';
    $redirectUri  = 'http://localhost';
    
    $auth = new Auth($clientId, $clientSecret, $redirectUri, true, true);
    
    $authenticationLink = $auth->getAuthenticationLink(); // returns an authentication link after passing the authentication, Allegro returns the code that is needed to get the necessary tokens
	$codeVerifier       = $auth->getCodeVerifier();       // returns an code verifier needed for authorization
    
    $auth->authorize($code, $codeVerifier); // returns response with access and refresh token

## Basic usage
	use Olekjs\Allegro\Requests\Request;

    $request = new Request($accessToken);
    $response = $request->getAllCategories();

