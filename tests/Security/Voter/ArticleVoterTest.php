<?php

namespace App\Tests\Security\Voter;

use App\Entity\Admin;
use App\Entity\Article;
use App\Security\Voter\ArticleVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ArticleVoterTest extends TestCase
{
    private $voter;

    protected function setUp()
    {
        $this->voter = new ArticleVoter();
    }

    public function testVoteOnSometingElse()
    {
        $token = $this->prophesize(TokenInterface::class);

        $this->assertSame(VoterInterface::ACCESS_ABSTAIN, $this->voter->vote($token->reveal(), null, ['FOOBAR']));
    }

    public function testVoteWhenNotConnected()
    {
        $article = new Article(new Admin());
        $token = $this->prophesize(TokenInterface::class);

        $this->assertSame(VoterInterface::ACCESS_DENIED, $this->voter->vote($token->reveal(), $article, ['ARTICLE_EDIT']));
    }

    public function provideVoteTests()
    {
        $admin = new Admin();
        $admin->setRoles([]);
        $article = new Article(new Admin());
        $article->setCategory('PHP');
        yield 'admin without role can not edit' => [VoterInterface::ACCESS_DENIED, $admin, $article];

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $article = new Article(new Admin());
        $article->setCategory('PHP');
        yield 'ROLE_ADMIN can edit everything' => [VoterInterface::ACCESS_GRANTED, $admin, $article];

        $admin = new Admin();
        $admin->setRoles(['ROLE_ARTICLE_CATEGORY_PHP']);
        $article = new Article(new Admin());
        $article->setCategory('PHP');
        yield 'ROLE_ARTICLE_CATEGORY_PHP can edit PHP article' => [VoterInterface::ACCESS_GRANTED, $admin, $article];

        $admin = new Admin();
        $admin->setRoles(['ROLE_ARTICLE_CATEGORY_PHP']);
        $article = new Article(new Admin());
        $article->setCategory('Golang');
        yield 'ROLE_ARTICLE_CATEGORY_PHP can not edit Golang article' => [VoterInterface::ACCESS_DENIED, $admin, $article];
    }

    /** @dataProvider provideVoteTests */
    public function testVote(int $expected, Admin $admin, Article $article)
    {
        $token = new UsernamePasswordToken($admin, 'password', 'provider_key', $admin->getRoles());

        $this->assertSame($expected, $this->voter->vote($token, $article, ['ARTICLE_EDIT']));
    }
}
