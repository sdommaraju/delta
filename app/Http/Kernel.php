<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		//'App\Http\Middleware\VerifyCsrfToken',
	    'LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware',
	    'Barryvdh\Cors\HandleCors'
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',
	    'check-authorization-params' => 'LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware',
	    'csrf' => 'Laravel\Lumen\Http\Middleware\VerifyCsrfToken',
	    
	    'oauth' => 'LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware',
	    'oauth-user' => 'LucaDegasperi\OAuth2Server\Middleware\OAuthUserOwnerMiddleware',
	    'oauth-client' => 'LucaDegasperi\OAuth2Server\Middleware\OAuthClientOwnerMiddleware',
        'oauth-admin' => 'App\Http\Middleware\oAuthAdmin'
	];

}
