# Telegram formate message entities html [![License][packagist-license]][license-url]

[![Downloads][packagist-downloads]][packagist-url]
[![Telegram][Telegram-image]][Telegram-url]

## Installation
**Using Composer:**
```
composer require tioffs/fentities
```

## Supports entities

- messageEntityItalic
- messageEntityBold
- messageEntityCode
- messageEntityPre
- messageEntityStrike
- messageEntityUnderline
- messageEntityBlockquote
- messageEntityTextUrl
- messageEntityMention
- messageEntityUrl


## Example
```php
$message  = [
                "_" => "message",
                "out" => false,
                "mentioned" => false,
                "media_unread" => false,
                "silent" => true,
                "post" => true,
                "from_scheduled" => false,
                "legacy" => false,
                "id" => 83943,
                "to_id" => [
                    "_" => "peerChannel",
                    "channel_id" => 123456789
                ],
                "date" => 1565361063,
                "message" => "PHP is a popular general-purpose scripting language that is especially suited to web development. http://php.net",
                "media" => [],
                "entities" => [
                    [
                        "_" => "messageEntityBold",
                        "offset" => 0,
                        "length" => 96
                    ],
                    [
                        "_" => "messageEntityUrl",
                        "offset" => 98,
                        "length" => 14
                    ]
                ],
                "views" => 34566
            ];

require_once 'vendor/autoload.php';
$text = Formats\Message::html($message);
```
## Result
```html
<b>PHP is a popular general-purpose scripting language that
is especially suited to web development.</b>
<a href="http://php.net">http://php.net</a>
```

----

Made with &#9829; from the [@tioffs][tioffs-url]

[tioffs-url]: https://timlab.ru/
[license-url]: https://github.com/tioffs/fentities/blob/master/LICENSE

[telegram-url]: https://t.me/joinchat/C9JmzQ-fc3SKXI0D-9h-uw
[telegram-image]: https://img.shields.io/badge/Telegram-Join%20Chat-blue.svg?style=flat

[packagist-url]: https://packagist.org/packages/tioffs/fentities
[packagist-license]: https://img.shields.io/github/license/tioffs/fentities
[packagist-downloads]: https://img.shields.io/packagist/dm/tioffs/fentities