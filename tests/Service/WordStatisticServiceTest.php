<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\WordStatisticService;
use Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TypeError;

class WordStatisticServiceTest extends TestCase
{
    private WordStatisticService|MockObject $service;

    protected function setUp(): void
    {
        $this->service = new WordStatisticService();
    }
    /**
     * @test
     *
     * @group unit
     *
     * @dataProvider valid_data_string
     */
    public function testMadeStatistics(string $text)
    {
        $result = $this->service->madeStatistics($text);

        static::assertIsArray($result);
        static::assertIsInt($result['numberOfCharacters']);
        static::assertIsInt($result['numberOfWords']);
        static::assertIsInt($result['numberOfSentences']);
        static::assertIsArray($result['frequencyOfCharacters']);
        static::assertIsArray($result['distributionCharactersPercentage']);
        static::assertIsNumeric($result['averageWordLength']);
        static::assertIsArray($result['mostUsedWords']);
        static::assertIsArray($result['mostLongestWords']);
        static::assertIsArray($result['mostShortestWords']);
        static::assertIsArray($result['mostLongestSentences']);
        static::assertIsArray($result['mostShortestSentences']);
        static::assertIsNumeric($result['countPalindromeWords']);
        static::assertIsArray($result['longestPalindromeWords']);
        static::assertIsString($result['isTextPalindrome']);
        static::assertIsString($result['reversText']);
        static::assertIsString($result['reversTextByWord']);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @dataProvider valid_data_string
     */
    public function testNumberOfCharacters(string $text)
    {
        $result = $this->service->numberOfCharacters($text);

        static::assertIsInt($result);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @dataProvider invalid_data_string
     */
    public function testFailNumberOfCharacters(mixed $text)
    {
        $this->expectException(TypeError::class);

        $this->service->numberOfCharacters($text);
    }

    public function valid_data_string(): Generator
    {
        yield [''];

        yield ['test text'];

        yield ['Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et 
        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip 
        ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu 
        fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt 
        mollit anim id est laborum.'];

        yield ['never odd or even'];

        yield ['Eva, can I see bees in a cave? Was it a cat I saw? I did, did I?'];
    }

    public function invalid_data_string(): Generator
    {
        yield [-11];

        yield [1];

        yield [['apple']];
    }
}
