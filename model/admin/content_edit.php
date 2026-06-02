<?php

require_once FILE_SITE_CONTENT_HELPER;
include FILE_CONNECT;

$contentSections = [
    'Home hero' => homeHeroContentDefaults(),
    'Kontakt stranica' => contactContentDefaults(),
    'Footer' => footerContentDefaults(),
];

$contentTitles = editableContentTitles();
$siteContent = loadEditableContent($conn);
