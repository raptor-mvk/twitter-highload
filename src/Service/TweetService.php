<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Tweet;
use App\Entity\User;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

/**
 * @author Mikhail Kamorin aka raptor_MVK
 *
 * @copyright 2020, raptor_MVK
 */
final class TweetService
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var ProducerInterface */
    private $producer;

    public function __construct(EntityManagerInterface $entityManager, ProducerInterface $producer)
    {
        $this->entityManager = $entityManager;
        $this->producer = $producer;
    }

    public function saveTweet(int $authorId, string $text): bool {
        $tweet = new Tweet();
        $userRepository = $this->entityManager->getRepository(User::class);
        $author = $userRepository->find($authorId);
        if (!($author instanceof User)) {
            return false;
        }
        $tweet->setAuthor($author);
        $tweet->setText($text);
        $this->entityManager->persist($tweet);
        $this->entityManager->flush();
        $this->producer->publish($tweet->toAMQPMessage());

        return true;
    }
}