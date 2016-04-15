<?php

namespace Nas1k\LaravelSes\Http\Controllers\Report;

use Nas1k\LaravelSes\Domain\Entity\ReportRepository;
use Illuminate\Routing\Controller as BaseController;

class ListController extends BaseController
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function create()
    {
        $rows = $this->reportRepository->findAll();

        return view(
            'list',
            ['items' => json_encode($rows)]
        );
    }
}
