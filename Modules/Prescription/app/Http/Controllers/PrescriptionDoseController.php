<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Prescription\Http\Requests\PrescriptionDoseRequest;
use Modules\Prescription\Models\PrescriptionDose;

class PrescriptionDoseController extends BaseController
{
    protected string $model = PrescriptionDose::class;

    protected string $title = 'Prescription Dose';

    protected string $views = 'prescription::dose';

    protected string $parameter = 'prescription_dose';

    protected bool $inlineForm = true;

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function store(PrescriptionDoseRequest $request): RedirectResponse
    {
        return $this->saveData($request);
    }
}
