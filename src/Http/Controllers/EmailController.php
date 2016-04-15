<?php

namespace Nas1k\LaravelSes\Http\Controllers;

use Nas1k\LaravelSes\Domain\EmailBuilder;
use Nas1k\LaravelSes\Domain\Entity\Report;
use Nas1k\LaravelSes\Domain\Entity\ReportRepository;
use Aws\Exception\AwsException;
use Aws\Ses\SesClient;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class EmailController extends BaseController
{
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
        SesClient $sesClient,
        EmailBuilder $emailBuilder,
        ReportRepository $reportRepository
    ) {
        $this->sesClient = $sesClient;
        $this->emailBuilder = $emailBuilder;
        $this->reportRepository = $reportRepository;
    }

    public function send()
    {
        $message =$this->emailBuilder->setSource(request()->input('source'))
                ->setDestinationTo(request()->input('destination'))
                ->setMessageText(request()->input('subject'), request()->input('message'));
        $report = new Report();
        $report->setMessage($message);
        try {
            $this->sesClient->sendEmail($message->build());
            $report->setStatus(Report::STATUS_SUCCESS);
        } catch (AwsException $e) {
            Log::info($e);
            $report->setStatus(Report::STATUS_ERROR);
            return response()->json(['status' => Report::STATUS_ERROR, 'error' => $e->getMessage()]);
        }
        $this->reportRepository->save($report);
        return response()->json(['status' => Report::STATUS_SUCCESS]);
    }
}
