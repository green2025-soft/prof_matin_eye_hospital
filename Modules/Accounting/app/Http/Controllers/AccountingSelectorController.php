<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Accounting\Models\AccountHead;
use Modules\Core\Http\Controllers\BaseController;

class AccountingSelectorController extends BaseController
{
    public function ledgers(Request $request): Response
    {
        $search = $request->string('search')->trim()->substr(0, 80)->toString();
        $query = AccountHead::where('depth', 5);

        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'))->where('is_transaction', true);
        }

        if ($search !== '') {
            $term = '%'.addcslashes($search, '\\%_').'%';
            $query->where(fn (Builder $matches) => $matches->whereLike('name', $term)
                ->orWhereLike('code', $term));
        }

        $data = [
            'records' => $query->orderBy('code')->orderBy('id')
                ->simplePaginate(20, ['id', 'code', 'name', 'active'], 'page', max(1, $request->integer('page', 1))),
        ];

        return response()->view('accounting::selectors.ledgers', $data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
