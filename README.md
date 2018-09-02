# BotMan Bundle
> BotMan integration for Symfony

This is a bundle to use [BotMan](https://botman.io/) framework in Symfony.

## Supported drivers

Only the marked drivers are supported. See the official driver documentation to have more information:

* [ ] Amazon Alexa
* [ ] Cisco Spark
* [x] [Facebook Messenger](https://botman.io/2.0/driver-facebook-messenger)
* [ ] Hangouts Chat
* [ ] HipChat
* [ ] Microsoft Bot Framework
* [ ] Nexmo
* [ ] Slack
* [x] [Telegram](https://botman.io/2.0/driver-telegram)
* [ ] Twilio
* [ ] Web
* [ ] WeChat

## Installation

### Step 1: Download the bundle

Install the library via [Composer](https://getcomposer.org/) by
running the following command:

```bash
composer require sgomez/botman-bundle
```

### Step 2: Enable the bundle

You can skip this step if you are using _Symfony Flex_.

Enable the bundle in your `app/AppKernel.php` file:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sgomez\Bundle\BotmanBundle\BotmanBundle(),
        // ...
    );
}
```

### Step 3: Configure the bundle

You can see a template of config file by running `console config:dump-reference botman`.
 
```yaml
botman:
    drivers:
        telegram:
            parameters:
                token: ~
        facebook:
            parameters:
                token: ~
                app_secret: ~
                verification: ~
                start_button_payload: ~         # Optional
                greeting:                       # Optional
                    # Array of
                    -
                        locale:               ~ # Required
                        text:                 ~ # Required
                whitelisted_domains:  []        # Optional
``` 


You only must add the _drivers_ than you want to use. 

### Step 4: Configure the webhook

Add the router configuration for the webhook in your _routing.yaml_:

```yaml
BotmanBundle:
    resource: .
    type: extra
```

The _webhook path_ is `/botman` by default, but we recommend to change it by security issues.

```yaml
botman:
    path:                 /botman57637357-65ce-4faf-a9d9-ee2c13011d87
```


Now, you must create the webhook controller class. By default is configured as `App\Controller\WebhookController`: 

```php
<?php
// file: src/Controller/WebhookController.php

declare(strict_types=1);

namespace App\Controller;

use BotMan\BotMan\BotMan;
use Symfony\Component\HttpFoundation\Response;

class WebhookController
{
    public function __invoke(BotMan $bot): Response
    {
        // Add your logic here
        
        // Echo bot example
        $bot->fallback(function (BotMan $bot): void {
            $bot->reply($bot->getMessage()->getText());
        });

        // Stop touching
        $bot->listen();

        return new Response('', Response::HTTP_OK);
    }
}
```

If you want to change the controller class or you are not using _Symfony Flex_, you will need to specify the
controller class in the config file:

```yaml
botman:
    controller:           AppBundle\Controller\WebhookController
``` 

To know more about how to listen or send messages read the [official documentation](https://botman.io/2.0/installation).

## Services

This bundle configure the _Symfony container_ in the _BotMan_ instance. So, you can inject dependencies via 
constructor in [Conversations](https://botman.io/2.0/conversations) classes.

In you want to use BotMan instance in your services, you can inject `BotMan\BotMan\BotMan` class or `botman` alias. 

## Drivers command

Some driver will have commands to help to configure it:

### Facebook

The next commands are available:

| Command                             | Description                                                       |
|-------------------------------------|-------------------------------------------------------------------|
| `botman:facebook:greeting`          | Configure greeting message from driver configuration              |
| `botman:facebook:info`              | Retrieve the current values of Messenger Profile Properties       |
| `botman:facebook:start-button`      | Configure Messenger `Get started` button from driver configuration|
| `botman:facebook:whitelist-domains` | Configure Messenger whitelisted domains from driver configuration |

### Telegram

| Command                             | Description                                                       |
|-------------------------------------|-------------------------------------------------------------------|
| `botman:telegram:info`              | Retrieve the current values of Telegram bot and its webhook status|
| `botman:telegram:webhook`           | Configure the system webhook to be used by Telegram bot           |

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTION.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/sgomez/botman-bundle/tags).
However, this project is in very alpha status and config file format can change very quickly. 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
