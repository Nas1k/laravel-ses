<?php

namespace Nas1k\LaravelSes\Http\Controllers;

use Nas1k\LaravelSes\Domain\EmailBuilder;
use Nas1k\LaravelSes\Domain\Entity\Report;
use Nas1k\LaravelSes\Domain\Entity\ReportRepository;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Aws\Ses\SesClient;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class ProcessQController extends BaseController
{
    /**
     * @var SqsClient
     */
    protected $sqsClient;
    /**
     * @var SesClient
     */
    protected $sesClient;

    /**
     * @var EmailBuilder
     */
    protected $emailBuilder;

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    public function __construct(
        SqsClient $sqsClient,
        SesClient $sesClient,
        EmailBuilder $emailBuilder,
        ReportRepository $reportRepository
    ) {
        $this->sqsClient = $sqsClient;
        $this->sesClient = $sesClient;
        $this->emailBuilder = $emailBuilder;
        $this->reportRepository = $reportRepository;
    }

    public function process()
    {
        try {
            $result = $this->sqsClient->receiveMessage(
                [
                    'QueueUrl' => 'https://sqs.us-west-2.amazonaws.com/406192376864/ses-queue',
                ]
            );
            if ($result->hasKey('Messages')) {
                foreach ($result->get('Messages') as $msg) {
                    $body = json_decode($msg['Body'], true);
                    if ($body['Message']) {
                        $request = json_decode($body['Message'], true);
                        $this->sesClient->sendEmail($request);
                    }
                    $result = $this->sqsClient->deleteMessage(
                        [
                            'QueueUrl' => 'https://sqs.us-west-2.amazonaws.com/406192376864/ses-queue',
                            'ReceiptHandle' => $msg['ReceiptHandle']
                        ]
                    );
                    if ($result->get('@metadata')['statusCode'] === 200) {
                        return response()->json(['status' => Report::STATUS_SUCCESS]);
                    }
                }
            }
        } catch (AwsException $e) {
            Log::info($e);
            return response()->json(['status' => Report::STATUS_ERROR, 'error' => $e->getMessage()]);
        }
        return response()->json(['status' => 'empty_queue']);
    }
}
