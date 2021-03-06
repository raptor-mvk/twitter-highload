<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Mikhail Kamorin aka raptor_MVK
 *
 * @copyright 2020, raptor_MVK
 */
final class SubscriptionService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return int[]
     */
    public function getFollowerIds(int $authorId): array
    {
        $subscriptions = $this->getSubscriptionsByAuthorId($authorId);
        $mapper = static function(Subscription $subscription) {
            return $subscription->getFollower()->getId();
        };

        return array_map($mapper, $subscriptions);
    }

    /**
     * @return User[]
     */
    public function getFollowers(int $authorId): array
    {
        $subscriptions = $this->getSubscriptionsByAuthorId($authorId);
        $mapper = static function(Subscription $subscription) {
            return $subscription->getFollower();
        };

        return array_map($mapper, $subscriptions);
    }

    /**
     * @return int[]
     */
    public function getAuthorIds(int $followerId): array
    {
        $subscriptions = $this->getSubscriptionsByFollowerId($followerId);
        $mapper = static function(Subscription $subscription) {
            return $subscription->getAuthor()->getId();
        };

        return array_map($mapper, $subscriptions);
    }

    /**
     * @return User[]
     */
    public function getAuthors(int $followerId): array
    {
        $subscriptions = $this->getSubscriptionsByFollowerId($followerId);
        $mapper = static function(Subscription $subscription) {
            return $subscription->getAuthor();
        };

        return array_map($mapper, $subscriptions);
    }

    public function subscribe(int $authorId, int $followerId): bool
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $author = $userRepository->find($authorId);
        if (!($author instanceof User)) {
            return false;
        }
        $follower = $userRepository->find($followerId);
        if (!($follower instanceof User)) {
            return false;
        }

        $subscription = new Subscription();
        $subscription->setAuthor($author);
        $subscription->setFollower($follower);
        $this->entityManager->persist($subscription);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return Subscription[]
     */
    private function getSubscriptionsByAuthorId(int $authorId): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $author = $userRepository->find($authorId);
        if (!($author instanceof User)) {
            return [];
        }
        $subscriptionRepository = $this->entityManager->getRepository(Subscription::class);
        return $subscriptionRepository->findBy(['author' => $author]) ?? [];
    }

    /**
     * @return Subscription[]
     */
    private function getSubscriptionsByFollowerId(int $followerId): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $follower = $userRepository->find($followerId);
        if (!($follower instanceof User)) {
            return [];
        }
        $subscriptionRepository = $this->entityManager->getRepository(Subscription::class);
        return $subscriptionRepository->findBy(['follower' => $follower]) ?? [];
    }
}