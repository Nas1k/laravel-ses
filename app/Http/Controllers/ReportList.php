<?php

namespace App\Http\Controllers;

use App\Domain\Entity\ReportRepository;
use Illuminate\Routing\Controller as BaseController;

class ReportList extends BaseController
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function __invoke()
    {
        $rows = $this->reportRepository->findAll();

        return view(
            'list',
            ['items' => json_encode($rows)]
        );
    }
}
