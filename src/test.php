<?php
namespace Dhtml\FlarumLanguageTranslator;

use Dhtml\FlarumLanguageTranslator\Services\LibreHTMLTranslator;

$html = "<html><body><p>Hello World!</p><p>This is a test.</p></body></html>";
$targetLang = 'es'; // Spanish

$translator = new LibreHTMLTranslator($html, $targetLang,"fb3d22c6-f6d3-4e9e-bd3d-2cb99285fccd");
$translatedHTML = $translator->translateHTML();

echo $translatedHTML;

