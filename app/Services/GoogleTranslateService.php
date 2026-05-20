<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleTranslateService
{
    protected $translator;
    protected $supportedLanguages = [
        'en' => 'en', // English
        'kh' => 'km', // Khmer (Google uses 'km' code)
        'zh' => 'zh-CN', // Simplified Chinese
    ];

    public function __construct()
    {
        $this->translator = new GoogleTranslate();
    }

    /**
     * Translate text to target language
     *
     * @param string $text
     * @param string $targetLang
     * @param string $sourceLang
     * @return string|null
     */
    public function translate($text, $targetLang, $sourceLang = 'en')
    {
        try {
            if (empty($text)) {
                return null;
            }

            // Map our language codes to Google's codes
            $sourceCode = $this->supportedLanguages[$sourceLang] ?? $sourceLang;
            $targetCode = $this->supportedLanguages[$targetLang] ?? $targetLang;

            // Set source and target languages
            $this->translator->setSource($sourceCode);
            $this->translator->setTarget($targetCode);

            // Translate
            $translated = $this->translator->translate($text);

            return $translated ?: null;
        } catch (Exception $e) {
            Log::error('Google Translate Error: ' . $e->getMessage(), [
                'text' => $text,
                'source' => $sourceLang,
                'target' => $targetLang
            ]);
            return null;
        }
    }

    /**
     * Translate text to multiple languages
     *
     * @param string $text
     * @param array $targetLangs
     * @param string $sourceLang
     * @return array
     */
    public function translateToMultiple($text, array $targetLangs, $sourceLang = 'en')
    {
        $translations = [];

        foreach ($targetLangs as $lang) {
            if ($lang === $sourceLang) {
                $translations[$lang] = $text;
                continue;
            }

            $translations[$lang] = $this->translate($text, $lang, $sourceLang);
        }

        return $translations;
    }

    /**
     * Batch translate multiple texts to target language
     *
     * @param array $texts
     * @param string $targetLang
     * @param string $sourceLang
     * @return array
     */
    public function batchTranslate(array $texts, $targetLang, $sourceLang = 'en')
    {
        $translations = [];

        foreach ($texts as $key => $text) {
            $translations[$key] = $this->translate($text, $targetLang, $sourceLang);

            // Add small delay to avoid rate limiting
            usleep(100000); // 100ms delay
        }

        return $translations;
    }

    /**
     * Auto-translate missing translations for a TranslationKey model
     *
     * @param \App\Models\TranslationKey $translationKey
     * @param string $sourceLang
     * @return array
     */
    public function autoTranslateKey($translationKey, $sourceLang = 'en')
    {
        $sourceText = $translationKey->getTranslation($sourceLang);

        if (empty($sourceText)) {
            return [
                'success' => false,
                'message' => 'Source text is empty'
            ];
        }

        $translated = [];
        $missingLanguages = $translationKey->getMissingTranslations();

        foreach ($missingLanguages as $lang) {
            if ($lang === $sourceLang) {
                continue;
            }

            $result = $this->translate($sourceText, $lang, $sourceLang);
            if ($result) {
                $translationKey->setTranslation($lang, $result);
                $translated[$lang] = $result;
            }

            // Small delay between translations
            usleep(100000);
        }

        if (!empty($translated)) {
            $translationKey->auto_translated = true;
            $translationKey->save();

            return [
                'success' => true,
                'message' => 'Translations completed',
                'translations' => $translated
            ];
        }

        return [
            'success' => false,
            'message' => 'No translations were performed'
        ];
    }

    /**
     * Get supported languages
     *
     * @return array
     */
    public function getSupportedLanguages()
    {
        return array_keys($this->supportedLanguages);
    }

    /**
     * Check if language is supported
     *
     * @param string $lang
     * @return bool
     */
    public function isLanguageSupported($lang)
    {
        return isset($this->supportedLanguages[$lang]);
    }
}
