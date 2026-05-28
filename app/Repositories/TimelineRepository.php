<?php

namespace App\Repositories;

use App\Models\SubmissionTimeline;

class TimelineRepository extends BaseRepository
{
    public function __construct(SubmissionTimeline $model)
    {
        parent::__construct($model);
    }

    public function canSubmit(string $tahunPeriode): array
    {
        return SubmissionTimeline::canSubmit($tahunPeriode);
    }

    public function canViewResults(string $tahunPeriode): array
    {
        return SubmissionTimeline::canViewResults($tahunPeriode);
    }

    public function getAllTimelines()
    {
        return $this->model->all();
    }
}
