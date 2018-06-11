# Jira API client for Laravel 5.4+

\*This a fork of [rjvandoesburg/laravel-jira-rest-client](https://github.com/rjvandoesburg/laravel-jira-rest-client)

Perform various operations of [Jira APIs](https://developer.atlassian.com/cloud/jira/platform/rest/) with Laravel 5.4+

The aim of the package is to make it easier to communicate with the API. By default the response from the request is not altered in any way.
By creating your own implementation or de simple helpers provided with the package you are able to integrate Jira the way you like.

## Installation

To get the latest version of `laravel-jira-rest-client`, run the following command
```shell
composer require spiria-digital/laravel-jira-rest-client
```
Do note that not all methods have been implemented yet.

Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

## Laravel 5.4:

If you don't use auto-discovery, add the ServiceProvider to the providers array in `config/app.php`
```php
'providers' => [
    // ...

    Atlassian\JiraRest\JiraRestServiceProvider::class,
],
```

Also locate the `Aliases` key in your `config/app.php` file and register the Facade:

```php
'aliases' => [
    // ...

    'Jira' => Atlassian\JiraRest\Facades\Jira::class,
],
```
Copy the package config to your local config with the publish command:
```shell
php artisan vendor:publish --provider="Atlassian\JiraRest\JiraRestServiceProvider"
```

Update the .env with your proper credentials using JIRA variables from `config/atlassian/jira.php`.

## Usage

The core of this package is a direct reflection of the Jira API mean that all request classes don't format the response you get from the API.
This desicion was made so the package is more versitile allowing users to handle the response of the requests to their own wishes. 

For example, to fetch a specific issue you could do the following
```php
$request = new \Atlassian\JiraRest\Requests\Issue\IssueRequest;
$response = $request->get('ISSUE-3');
```

All responses are an instance of `\GuzzleHttp\Psr7\Response` [Read more](http://docs.guzzlephp.org/en/stable/psr7.html) so in order to get the json response you could do the following:
```php
$response = json_decode($response->getBody(), true);
``` 
Which will return a response like seen in the [API](https://developer.atlassian.com/cloud/jira/platform/rest/#api-api-2-issue-issueIdOrKey-get)

### Helpers
Now because for the most part you don't want to spend time writing the requests yourself there are some useful helpers to get you communicating with the api.

To fetch a single issue you can use the following code:
```php
$issue = jira()->issue('ISSUE-3')->get();
```

Or use the facade if you prefer:
```php
$issue = \Jira::issue('ISSUE-3')->get();
```

### Middleware
To alter the Guzzle Client used for requests you can add middleware to alter the options. To add new middleware you need to alter `config/atlassian/jira.php` and add the class to the `client_options` array.

#### Basic Auth
```php
'client_options' => [
    'auth' => \Atlassian\JiraRest\Requests\Middleware\BasicAuthMiddleware::class,
],
```
By default the `BasicAuthMiddleware` is added and used for authentication with Jira. (Sessions is WIP)

#### OAuth 1.0
```php
'client_options' => [
    'auth' => \Atlassian\JiraRest\Requests\Middleware\OAuthMiddleware::class,
],
```
**Impersonation**

To impersonate a user through Jira requests you must set `JIRA_IMPERSONATE=true` in your .env file. 

Once impersonation is enabled, laravel will use the authentificated user `name` by default. However, it's also possible to impersonate a user manually by sending a userName to the Jira constructor.

```php
/*auth user*/
$issue = \Jira::issue('ISSUE-3')->get();
/* Specific user */
$issue = jira("jdoe")->issue('ISSUE-123')->get();
```

**JIRA Setup for Impersonation**
1. Follow [Jira documentation](https://developer.atlassian.com/server/jira/platform/oauth/#see-it-in-action) to generate an RSA public/private key pair.
2. Go to Jira --> Application Links (Admin)
3. Create a new link with your server url 
4. Ignore the "No response" warning
5. Enter anything in all the field and keep "Create incoming link unchecked". Jira has a weird behaviour when it comes to setting up app links. If you create your incoming link now, you won't have access to 2-Legged auth (Impersonation).
6. Click continue (ignore the warning). This should have created your new app link.
7. Edit that link (notice there are no Outgoing info even if you added dummy info at creation).
8. You may now enter all the info for OAuth and setup impersonation (Allow 2-Legged OAuth).
  

ps. I'm not quite happy with the middleware as it is implemented at this time but I do want to incorporate them in a way.

## TODO
- More helpers
- Implement missing
- Middleware
- Better README
- Sessions auth
- A way to alter the request before it is send out (globally for each request and possibility for specific requests)
- Tests

