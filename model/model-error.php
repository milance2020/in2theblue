<?php

http_response_code(404);

$_output['view'] = 'errors/404';
$_output['html_model'] = 'error';

require_once FILE_SEO_HELPER;

$_output['meta_title'] = 'Stranica nije pronadjena';
$_output['meta_description'] = 'Trazena stranica ne postoji.';
$_output['meta_robots'] = 'noindex, follow';
