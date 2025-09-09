<?php

namespace App\Services\GetShortCutFromURL;

class GetShortCutFromURL
{
    public static function get($link, $tag = '', $subTag = '')
    {
        $url = parse_url($link);

        $url = $url['host'];

        return 'https://favicon.yandex.net/favicon/' . $url;
    }
}
