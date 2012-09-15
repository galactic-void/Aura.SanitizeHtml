<?php

$loader->add('Aura\SanitizeHtml\\', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');


$di->set('sanitize_html', function() use ($di) {
    return new \Aura\SanitizeHtml\Sanitize;
});

$di->params['Aura\View\HelperLocator']['registry']['sanitize'] = function() use ($di) {
    return new \Aura\SanitizeHtml\View\Helper\Sanitize($di->get('sanitize_html'));
};