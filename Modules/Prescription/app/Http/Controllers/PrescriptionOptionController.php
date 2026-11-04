<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;
use Modules\Prescription\Http\Requests\PrescriptionOptionRequest;
use Modules\Prescription\Models\PrescriptionOption;

class PrescriptionOptionController extends BaseController
{
    protected string $model = PrescriptionOption::class;

    protected string $title = 'Prescription option';

    protected string $views = 'prescription::options';

    protected string $parameter = 'prescription_option';

    protected bool $inlineForm = true;

    protected array $routeFilters = [
        'section' => 'section',
    ];

    public function index(ListRequest $request): View
    {
        $data = [
            'sections' => PrescriptionOption::sections(),
        ];

        return $this->listData($request)
            ->with($data);
    }

    public function store(PrescriptionOptionRequest $request): RedirectResponse|JsonResponse
    {
        return $this->saveData($request, $request->expectsJson());
    }

    protected function formData(Account $actor, Model $record): array
    {
        return [
            'sections' => PrescriptionOption::sections(),
        ];
    }
}
