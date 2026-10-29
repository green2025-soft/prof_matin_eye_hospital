<?php

namespace Modules\Hospital\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Http\Requests\SelectorRequest;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Http\Requests\ReferenceRequest;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Services\ReferenceService;

class ReferenceController extends BaseController
{
    protected string $model = Reference::class;

    protected string $title = 'Reference';

    protected string $views = 'hospital::references';

    protected string $parameter = 'reference';

    protected array $selectorFields = ['id', 'reference_id', 'name', 'contact_no'];

    public function index(ListRequest $request): View
    {
        return $this->listData($request);
    }

    public function options(SelectorRequest $request): JsonResponse
    {
        $response = parent::options($request);
        $data = $response->getData(true);
        $data['results'] = array_map(fn (array $reference): array => $reference + [
            'text' => $reference['reference_id'].' — '.$reference['name'].' ('.$reference['contact_no'].')',
        ], $data['results']);

        return $response->setData($data);
    }

    public function store(ReferenceRequest $request, ReferenceService $references): RedirectResponse
    {
        $reference = $references->save($request->user('web'), $this->record($request), $request->payload(), (int) $request->attributes->get('branch_id'));

        return $this->redirectAfterWrite($reference)->with('status', 'Reference'.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    protected function formData(Account $actor, Model $record): array
    {
        $settings = Setting::values('hospital', $record->getConnectionName());

        return [
            'percentageFields' => [
                'consultation_percent' => $settings['reference_percentages_enabled'] && $settings['reference_consultation_percent_enabled'],
                'investigation_percent' => $settings['reference_percentages_enabled'] && $settings['reference_investigation_percent_enabled'],
                'operation_percent' => $settings['reference_percentages_enabled'] && $settings['reference_operation_percent_enabled'],
            ],
            'percentageDefaults' => [
                'consultation_percent' => $settings['reference_defaults_enabled'] ? $settings['reference_consultation_percent'] : '0.00',
                'investigation_percent' => $settings['reference_defaults_enabled'] ? $settings['reference_investigation_percent'] : '0.00',
                'operation_percent' => $settings['reference_defaults_enabled'] ? $settings['reference_operation_percent'] : '0.00',
            ],
        ];
    }
}
