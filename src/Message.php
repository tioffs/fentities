<?php

/**
 * Telegram core api formate message html
 * php version >=7.3
 *
 * @author    tioffs <me@timlab.ru>
 * @copyright 2019 tioffs <me@timlab.ru>
 * @license   https://opensource.org/licenses/AGPL-3.0 AGPLv3
 *
 * @link      https://github.ru/tioffs
 */

namespace Formats;

class Message
{
 
    /**
     * function formate message array
     *
     * @param array $message messages[0]['message','media','id'...]
     * @return array
     */
    public static function html(array $message): array
    {
        return self::message($message['message'], $message['entities'] ?? []) ?? [];
    }
 

    /**
     * message to html
     *
     * @param string $message
     * @param array $enties
     * @return string
     */
    private static function message(string $message = null, array $enties = []): string
    {
        $html = [
            'messageEntityItalic' => '<i>%s</i>',
            'messageEntityBold' => '<strong>%s</strong>',
            'messageEntityCode' => '<code>%s</code>',
            'messageEntityPre' => '<pre>%s</pre>',
            'messageEntityStrike' => '<strike>%s</strike>',
            'messageEntityUnderline' => '<u>%s</u>',
            'messageEntityBlockquote' => '<blockquote>%s</blockquote>',
            'messageEntityTextUrl' => '<a href="%s" target="_blank" rel="nofollow">%s</a>',
            'messageEntityMention' =>  '<a href="tg://resolve?domain=%s" rel="nofollow">%s</a>',
            'messageEntityUrl' => '<a href="%s" target="_blank" rel="nofollow">%s</a>',
        ];

        $enties = array_reverse($enties);
        foreach ($enties as $k) {
            if (isset($html[$k['_']])) {
                $text = self::substr($message, $k['offset'], $k['length']);
                if ($k['_'] == 'messageEntityTextUrl' || $k['_'] == 'messageEntityMention' || $k['_'] == 'messageEntityUrl') {
                    $url = $k['url'] ?? $text;
                    if (!preg_match('/(http:\/\/|https:\/\/|\/\/|@)/is', $url)) {
                        $url = str_replace('http://', '', $url);
                        $url = str_replace('https://', '', $url);
                        $url = str_replace('//', '', $url);
                        $url = '//' . $url;
                    }
                    $textFormate = sprintf($html[$k['_']], $url, $text);
                } else {
                    $textFormate = sprintf($html[$k['_']], $text);
                }

                $message = self::substr_replace($message, $textFormate, $k['offset'], $k['length']);
            }
        }
        /** html tag enties xss */
        $message = strtr($message, [
            '<script>' => '&lt;script&gt;',
            '</script>' => '&lt;&sol;script&gt;',
            '<img' => '&lt;img',
            '<iframe' => '&lt;iframe',
            '</iframe>' => '&lt;&sol;iframe&gt;',
            '<video' => '&lt;video',
            '</video>' => '&lt;&sol;video&gt;',
            '<audio' => '&lt;audio',
            '</audio>' => '&lt;&sol;audio&gt;',
            "\n" => "<br/>"
        ]);
        return $message;
    }

    /**
     * substr_replace UTF8 * emoji
     *
     * @param string $original
     * @param string $replacement
     * @param integer $position
     * @param integer $length
     * @return string
     */
    private static function substr_replace(string $original, string $replacement, int $position, int $length): string
    {
        $startString = self::substr($original, 0, $position);
        $endString = self::substr($original, $position + $length, self::strlen($original));
        $out = $startString . $replacement . $endString;
        return $out;
    }


    /**
     * strlen UTF8 * emoji
     *
     * @param string $text
     * @return void
     */
    private static function strlen(string $text):int
    {
        $length = 0;
        $textlength = strlen($text);
        for ($x = 0; $x < $textlength; $x++) {
            $char = ord($text[$x]);
            if (($char & 0xC0) != 0x80) {
                $length += 1 + ($char >= 0xf0);
            }
        }

        return $length;
    }

    /**
     * substr UTF8 * emoji
     *
     * @param string $text
     * @param int $offset
     * @param int $length
     * @return string
     */
    private static function substr(string $text, int $offset, int $length = null):string
    {
        $mb_text_length = self::strlen($text);
        if ($offset < 0) {
            $offset = $mb_text_length + $offset;
        }
        if ($length < 0) {
            $length = ($mb_text_length - $offset) + $length;
        } elseif ($length === null) {
            $length = $mb_text_length - $offset;
        }
        $new_text = '';
        $current_offset = 0;
        $current_length = 0;
        $text_length = strlen($text);
        for ($x = 0; $x < $text_length; $x++) {
            $char = ord($text[$x]);
            if (($char & 0xC0) != 0x80) {
                $current_offset += 1 + ($char >= 0xf0);
                if ($current_offset > $offset) {
                    $current_length += 1 + ($char >= 0xf0);
                }
            }
            if ($current_offset > $offset) {
                if ($current_length <= $length) {
                    $new_text .= $text[$x];
                }
            }
        }

        return $new_text;
    }
}
