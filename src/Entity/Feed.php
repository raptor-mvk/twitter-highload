<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;

/**
 * @author Mikhail Kamorin aka raptor_MVK
 *
 * @copyright 2020, raptor_MVK
 *
 * @Mapping\Table(
 *     name="feed",
 *     uniqueConstraints={@Mapping\UniqueConstraint(columns={"reader_id"})},
 * )
 * @Mapping\Entity
 * @Mapping\HasLifecycleCallbacks
 */
class Feed
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @Mapping\Column(name="id", type="bigint", unique=true)
     * @Mapping\Id
     * @Mapping\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     *
     * @Mapping\ManyToOne(targetEntity="User")
     * @Mapping\JoinColumns({
     *   @Mapping\JoinColumn(name="reader_id", referencedColumnName="id")
     * })
     */
    private $reader;

    /**
     * @var array | null
     *
     * @Mapping\Column(type="json_array", nullable=true)
     */
    public $tweets;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getReader(): User
    {
        return $this->reader;
    }

    public function setReader(User $reader): void
    {
        $this->reader = $reader;
    }

    public function getTweets(): ?array
    {
        return $this->tweets;
    }

    public function setTweets(?array $tweets): void
    {
        $this->tweets = $tweets;
    }
}