<?php


namespace App\Service;


use App\Helper\LoggerTrait;
use Http\Client\Exception;
use Nexy\Slack\Client;
use Nexy\Slack\Exception\SlackApiException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SlackClient
{

    use LoggerTrait;

    /**
     * @var Client $slack
     */
    private $slack;


    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }


    public function sendMessage(string $from, string $message)
    {
        $this->logInfo('Beaming a message to slack!!!', [
            'message' => $message
        ]);

        $slackMessage = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message);
        try {
            $this->slack->sendMessage($slackMessage);
        } catch (Exception | SlackApiException $e) {
            throw new NotFoundHttpException("No Slack message was sent");
        }
    }

}