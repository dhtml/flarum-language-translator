<?php

namespace Dhtml\FlarumLanguageTranslator\Services;

use Doctrine\DBAL\Driver\Exception;
use DOMDocument;

class LibreHTMLTranslator {
    private $html;
    private $targetLang;
    private $dom;
    /**
     * @var mixed
     */
    private $apiKey;

    public function __construct($html, $targetLang, $apiKey) {
        $this->apiKey = $apiKey;
        $this->html = $html;
        $this->targetLang = $targetLang;
        $this->dom = new DOMDocument();
        @$this->dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    private function extractTextNodes($node) {
        $texts = [];
        if ($node->nodeType == XML_TEXT_NODE) {
            $texts[] = $node;
        }
        foreach ($node->childNodes as $child) {
            $texts = array_merge($texts, $this->extractTextNodes($child));
        }
        return $texts;
    }

    function detectLanguage($text) {
        $url = "https://libretranslate.com/detect";
        $data = [
            'q' => $text,
            'api_key' => $this->apiKey // Add your API key here
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $result = curl_exec($ch);

        if ($result === FALSE) {
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        $response = json_decode($result, true);
        return $response[0]['language'] ?? null;
    }

    private function translateText($text, $sourceLang) {
        $url = "https://libretranslate.com/translate";
        $data = [
            'q' => $text,
            'source' => $sourceLang,
            'target' => $this->targetLang,
            'api_key' => $this->apiKey
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);

        if ($result === FALSE) {
            curl_close($ch);
            return $text;
        }

        curl_close($ch);

        $response = json_decode($result, true);
        return $response['translatedText'] ?? $text;
    }


    public function translateHTML() {
        $response = [
            "success" => false,
            "content" => "",
            "error" => null,
        ];

        try {

            // Extract text nodes
            $textNodes = $this->extractTextNodes($this->dom->documentElement);

            // Translate and replace text nodes
            foreach ($textNodes as $textNode) {
                $sourceLang = $this->detectLanguage($textNode->nodeValue);
                if ($sourceLang) {
                    $translatedText = $this->translateText($textNode->nodeValue, $sourceLang);
                    $textNode->nodeValue = $translatedText;
                }
            }

            // Return the translated HTML
            $response['success'] = true;
            $response['content'] = $this->dom->saveHTML();
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
            $this->logInfo("Libre API Failed: " . $e->getMessage());
        }

        return $response;
    }
}