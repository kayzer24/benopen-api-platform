<?php

namespace App\Tests\Unit;

use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    private Article $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->article = new Article();
    }

    public function testGetName(): void
    {
        $value = 'Test Name';
        $response = $this->article->setName($value);

        self::assertInstanceOf(Article::class, $response);
        self:self::assertEquals($value, $this->article->getName());
    }

    public function testGetContent(): void
    {
        $value = 'Test Content';
        $response = $this->article->setContent($value);

        self::assertInstanceOf(Article::class, $response);
        self:self::assertEquals($value, $this->article->getContent());
    }

    public function testGetAuthor(): void
    {
        $value = new User();
        $response = $this->article->setAuthor($value);

        self::assertInstanceOf(Article::class, $response);
        self:self::assertEquals($value, $this->article->getAuthor());
    }
}
