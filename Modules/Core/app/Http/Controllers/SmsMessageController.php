<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Modules\Core\Http\Requests\SmsMessageRequest;
use Modules\Core\Models\SmsMessage;
use Modules\Core\Services\SmsService;

class SmsMessageController extends BaseController
{
    protected string $model = SmsMessage::class;

    protected string $parameter = 'sms_message';

    public function index(Request $request): View
    {
        Gate::authorize('viewHistory', SmsMessage::class);
        $filters = $request->only(['status', 'page', 'per_page']);
        $status = $request->string('status')->toString();
        $query = SmsMessage::with('tenant:id,name')
            ->orderByDesc('id');

        if (array_key_exists($status, SmsMessage::STATUSES)) {
            $query->where('status', $status);
        }

        $data = [
            'pageTitle' => 'SMS history',
            'records' => $this->paginateQuery($query, $filters),
            'filters' => $filters,
            'routePrefix' => $this->routePrefix(),
            'statuses' => SmsMessage::STATUSES,
            'features' => config('settings.sms_features', []),
        ];

        return view('core::sms-history.index', $data);
    }

    public function show(SmsMessage $smsMessage): View
    {
        Gate::authorize('viewHistory', SmsMessage::class);
        $smsMessage->load('tenant:id,name');

        $data = [
            'pageTitle' => 'SMS #'.$smsMessage->getKey(),
            'message' => $smsMessage,
            'statusLabel' => SmsMessage::STATUSES[$smsMessage->status],
            'featureLabel' => config('settings.sms_features', [])[$smsMessage->feature] ?? $smsMessage->feature,
        ];

        return view('core::sms-history.show', $data);
    }

    public function resolve(SmsMessageRequest $request, SmsMessage $smsMessage, SmsService $sms): RedirectResponse
    {
        $sms->resolve($request->user('web'), $smsMessage, $request->validated('decision'));

        return Gate::allows('viewHistory', SmsMessage::class)
            ? redirect()
                ->route('core.sms-history.show', $smsMessage)
                ->with('status', 'SMS result updated.')
            : redirect()
                ->route('home')
                ->with('status', 'SMS result updated.');
    }
}
