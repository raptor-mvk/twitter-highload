<?php
declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Service\TweetService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;

/**
 * @author Mikhail Kamorin aka raptor_MVK
 *
 * @copyright 2020, raptor_MVK
 *
 * @Annotations\Route("/api/v1/tweet")
 */
final class TweetController extends AbstractFOSRestController
{
    /** @var TweetService */
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        $this->tweetService = $tweetService;
    }

    /**
     * @Annotations\Post("")
     *
     * @RequestParam(name="authorId", requirements="\d+")
     * @RequestParam(name="text")
     * @RequestParam(name="async", requirements="0|1", nullable=true)
     */
    public function postTweetAction(int $authorId, string $text, ?int $async): View
    {
        $success = $async === 1 ?
            $this->tweetService->saveTweetAsync($authorId, $text) :
            $this->tweetService->saveTweetSync($authorId, $text);
        $code = $success ? 200 : 400;

        return View::create(['success' => $success], $code);
    }
}