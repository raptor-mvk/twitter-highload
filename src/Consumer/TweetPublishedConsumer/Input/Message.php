<?php
declare(strict_types=1);

namespace App\Consumer\TweetPublishedConsumer\Input;

use DateTime;
use JsonException;
use Symfony\Component\Validator\Constraints;

/**
 * @author Mikhail Kamorin aka raptor_MVK
 *
 * @copyright 2020, raptor_MVK
 */
final class Message
{
    /**
     * @var int
     * @Constraints\Regex("/^\d+$/")
     */
    private $tweetId;

    /**
     * @throws JsonException
     */
    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->tweetId = $message['tweetId'];

        return $result;
    }

    public function getTweetId(): int
    {
        return $this->tweetId;
    }
}