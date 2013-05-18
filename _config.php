<?php
define('WIDGETS_WEATHER', basename(dirname(__FILE__)), 0);
define('WIDGETS_WEATHER_ICONS', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'weather_icons');
define('WIDGETS_WEATHER_ICONS_URL', str_replace($_SERVER['DOCUMENT_ROOT'] . "/", "", str_replace('\\', "/", dirname(__FILE__))) . '/images/weather_icons');
Requirements::css(WIDGETS_WEATHER . "/css/weather.css");
