<?php

declare(strict_types=1);

namespace App\Service;

use TypeError;

class WordStatisticService
{
    public function madeStatistics(?string $text): array
    {
        if (!is_string($text)) {
            throw new TypeError('Input must be an string.');
        }

        $timeStart = microtime(true);
        $text = iconv('UTF-8','windows-1251', $text);

        $numberOfCharacters = $this->numberOfCharacters($text);
        $numberOfWords = $this->numberOfWords($text);
        $numberOfSentences = $this->numberOfSentences($text);
        $frequencyOfCharacters = $this->frequencyOfCharacters($text);
        $distributionCharactersPercentage = $this->distributionCharactersPercentage($numberOfCharacters, $frequencyOfCharacters);
        $averageWordLength = $this->averageWordLength($text, $numberOfWords);
        $averageWordSentence = $this->averageWordSentence($text);
        $mostUsedWords = $this->mostUsedWords($text);
        $mostLongestWords = $this->mostLongestWords($text);
        $mostShortestWords = $this->mostShortestWords($text);
        $mostLongestSentences = $this->mostLongestSentences($text);
        $mostShortestSentences = $this->mostShortestSentences($text);

        $longestPalindromeWords = $this->longestPalindromeWords($text);
        $isTextPalindrome = $this->isTextPalindrome($text);
        $reversText = $this->reversText($text);
        $reversTextByWord = $this->reversTextByWord($text);

        $timeEnd = microtime(true);
        $reportTime = $timeEnd - $timeStart;

        $now = new \DateTime();
        $now = $now->format('d-M-Y H:i:s');

        return [
            'numberOfCharacters' => $numberOfCharacters,
            'numberOfWords' => $numberOfWords,
            'numberOfSentences' => $numberOfSentences,
            'frequencyOfCharacters' => $frequencyOfCharacters,
            'distributionCharactersPercentage' => $distributionCharactersPercentage,
            'averageWordLength' => $averageWordLength,
            'averageWordSentence' => $averageWordSentence,
            'mostUsedWords' => $mostUsedWords,
            'mostLongestWords' => $mostLongestWords,
            'mostShortestWords' => $mostShortestWords,
            'mostLongestSentences' => $mostLongestSentences,
            'mostShortestSentences' => $mostShortestSentences,
            'countPalindromeWords' => count($longestPalindromeWords) ?? 0,
            'longestPalindromeWords' => array_slice($longestPalindromeWords, 0, 10),
            'isTextPalindrome' => $isTextPalindrome ? 'Yes' : 'No',
            'reportTime' => $reportTime * 1000,
            'dateTime' => $now,
            'reversText' => $reversText,
            'reversTextByWord' => $reversTextByWord,
        ];
    }


    public function numberOfCharacters(string $text): int
    {
        $str = preg_replace("/[^A-Za-z]/","", $text);
        return strlen($str) ?? 0;
    }

    public function numberOfWords(string $text): int
    {
        return str_word_count($text) ?? 0;
    }

    public function numberOfSentences($text): int
    {
        return preg_match_all('/\S([.!?])(?!\w)/', $text) ?? 0;
    }

    public function frequencyOfCharacters(string $text): array
    {
        $result = [];

        foreach (count_chars($text, 1) as $letter => $count) {
            $result[chr($letter)] = $count;
        }

        return $result;
    }

    public function distributionCharactersPercentage(int $numberOfCharacters, array $frequencyOfCharacters): array
    {
        $result = [];

        foreach ($frequencyOfCharacters as $letter => $count) {
            $result[$letter] = $this->calculatePercent($numberOfCharacters, $count);
        }

        return $result;
    }

    public function averageWordLength(string $text,  int $numberOfCharacters): float|int
    {
        $result = 0;
        foreach (str_word_count($text, 1) as $word) {
            $result += $this->numberOfCharacters($word);
        }

        return $numberOfCharacters == 0 ? $result : $result / $numberOfCharacters;
    }

    public function averageWordSentence(string $text): float|int
    {
        $arraySentence = $this->getArraySentence($text);
        $arraySentenceCount = count($arraySentence);
        $result = 0;

        foreach ($arraySentence as $sentence) {
            $result += count(str_word_count($sentence, 2));
        }

        return $arraySentenceCount == 0 ? $result : $result / $arraySentenceCount;
    }

    public function mostUsedWords(string $text): array
    {
        $words = $this->getWords($text);
        $words = array_count_values($words);

        arsort($words);

        return array_slice($words, 0, 10);
    }

    public function mostLongestWords(string $text): array
    {
        $result = $this->getArrWordByLength($text);

        arsort($result);

        return array_slice($result, 0, 10);
    }

    public function mostShortestWords(string $text): array
    {
        $result = $this->getArrWordByLength($text);

        asort($result);

        return array_slice($result, 0, 10);
    }

    public function mostLongestSentences(string $text): array
    {
        $result = $this->getArrSentencesByLength($text);

        arsort($result);

        return array_slice($result, 0, 10);
    }

    public function mostShortestSentences(string $text): array
    {
        $result = $this->getArrSentencesByLength($text);

        asort($result);

        return array_slice($result, 0, 10);
    }

    public function reversText(string $text): string
    {
        return strrev($text);
    }

    public function reversTextByWord(string $text): string
    {
        $array = explode(" ", $text);
        $rArray = array_reverse($array);

        return implode(" ", $rArray);
    }

    public function longestPalindromeWords(string $text): array
    {
        $words = $this->getWords($text);
        $result = [];

        foreach ($words as $word) {
            if ($this->isTextPalindrome($word)) {
                $result[$word] = strlen($word);
            }
        }

        arsort($result);

        return $result;
    }

    public function isTextPalindrome(string $text): bool
    {
        $newText = preg_replace("/[^A-Za-z]/","", $text);

        $revText = strrev($newText);

        return $revText == $text;
    }

    private function calculatePercent(float $value1, float $value2): float
    {
        return round($value2 * 100 / $value1);
    }

    private function getArraySentence(string $text): array
    {
        return preg_split('/(?<=[.?!;:])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    private function getArrSentencesByLength(string $text): array
    {
        $result = [];

        $sentences = $this->getArraySentence($text);

        foreach ($sentences as $sentence) {
            $result[$sentence] = str_word_count($sentence);
        }

        return $result;
    }

    private function getArrWordByLength(string $text): array
    {
        $words = $this->getWords($text);
        $result = [];

        foreach ($words as $word) {
            $result[$word] = strlen($word);
        }

        return $result;
    }

    private function getWords(string $text): int|array
    {
        $text = strtolower($text);

        return str_word_count($text, 1);
    }
}