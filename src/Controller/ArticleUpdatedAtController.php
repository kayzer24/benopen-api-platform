<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Article;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleUpdatedAtController extends AbstractController
{
    public function __invoke(Article $data): Article
    {
        $data->setUpdatedAt(new DateTimeImmutable('tomorrow'));

        return $data;
    }
}
