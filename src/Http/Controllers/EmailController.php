<?php

namespace Nas1k\LaravelSes\Http\Controllers;

use Nas1k\LaravelSes\Domain\EmailBuilder;
use Nas1k\LaravelSes\Domain\Entity\Report;
use Nas1k\LaravelSes\Domain\Entity\ReportRepository;
use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class EmailController extends BaseController
{
    /**
     * @var SnsClient
     */
    protected $snsClient;

    /**
     * @var EmailBuilder
     */
    protected $emailBuilder;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    public function __construct(
        SnsClient $snsClient,
        EmailBuilder $emailBuilder,
        ReportRepository $reportRepository
    ) {
        $this->snsClient = $snsClient;
        $this->emailBuilder = $emailBuilder;
        $this->reportRepository = $reportRepository;
    }

    public function send()
    {
        $message =$this->emailBuilder->setSource(request()->input('source'))
                ->setDestinationTo(request()->input('destination'))
                ->setMessageText(request()->input('subject'), request()->input('message'));
        try {
            $res = $this->snsClient->publish(
                [
                    'Message' => json_encode($message),
                    'TopicArn' => 'arn:aws:sns:us-west-2:406192376864:send-message-to-ses',
                ]
            );
            var_dump($res->get('MessageId'));die;
        } catch (AwsException $e) {
            Log::info($e);
            return response()->json(['status' => Report::STATUS_ERROR, 'error' => $e->getMessage()]);
        }
        return response()->json(['status' => Report::STATUS_SUCCESS]);
    }
}
