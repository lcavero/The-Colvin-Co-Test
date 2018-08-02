<?php
/*
 * (c) lcavero <luiscaverodeveloper@gmail.com>
 */
declare(strict_types=1);

namespace Tests\AppBundle\Service;

use AppBundle\Exception\GithubServiceException;
use AppBundle\Service\GithubService;
use PHPUnit\Framework\TestCase;

class GithubServiceTest extends TestCase
{
    public function testCanRetrieveWordsFromValidRepository()
    {
        $srv = new GithubService();
        $words =  $srv->getWords('lcavero/DoctrinePaginatorBundle');
        $this->assertInternalType("array", $words);
        $this->assertNotEmpty($words);
    }

    public function testCantNotRetrieveWordsFromInvalidRepository()
    {
        $srv = new GithubService();
        $this->expectException(GithubServiceException::class);
        $srv->getWords('lcavero/InvalidRepository');
    }

    public function testCanRetrieveEmptyWordsFromNotPHPRepository()
    {
        $srv = new GithubService();
        $words =  $srv->getWords('lcavero/sails-hook-forms');
        $this->assertInternalType("array", $words);
        $this->assertEmpty($words);
    }
}