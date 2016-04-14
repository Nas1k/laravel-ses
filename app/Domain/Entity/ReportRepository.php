<?php

namespace App\Domain\Entity;

use Doctrine\ORM\EntityRepository;

class ReportRepository extends EntityRepository
{
    public function save(Report $report)
    {
        $this->getEntityManager()->persist($report);
        $this->getEntityManager()->flush();
    }
}
