@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Set the investigation details and rates.'])

    <form class="app-card p-3 p-md-4" action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <section class="app-form-section" aria-labelledby="investigation-form-heading">
            <h2 class="app-form-section-title" id="investigation-form-heading">
                <span class="app-form-section-icon">
                    <i class="fa-solid fa-flask" aria-hidden="true"></i>
                </span>Investigation details
            </h2>

            <div class="row g-3">
                 <div class="col-sm-6">
                    <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('name') }}" id="name" name="name" type="text" value="{{ getValue('name', $investigation) }}"  maxlength="255" autocomplete="off" required autofocus {{ inputAttrs('name') }}>
                    @error('name')<div class="invalid-feedback d-block" id="name-error">{{ $message }}</div>@enderror
                 </div>
                <div class="col-sm-6">
                    <label class="form-label" for="category_id">Category <span class="text-danger" aria-hidden="true">*</span></label>
                    <select class="{{ inputClass('category_id', 'form-select') }}" id="category_id" name="category_id" data-ajax-select data-url="{{ route('hospital.investigation-categories.options') }}" data-value-field="id" data-label-field="name" data-placeholder="Select a category" required {{ inputAttrs('category_id') }}>
                        <option value=""></option>
                        @if ($selectedCategory !== null)
                            <option value="{{ $selectedCategory->id }}" selected>{{ $selectedCategory->name }}</option>
                        @endif
                    </select>
                    @error('category_id')<div class="invalid-feedback d-block" id="category_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="single_rate">Single rate <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('single_rate') }}" id="single_rate" name="single_rate" type="text" inputmode="decimal" value="{{ getValue('single_rate', $investigation, '0.00') }}" maxlength="13" required {{ inputAttrs('single_rate') }}>
                    @error('single_rate')<div class="invalid-feedback d-block" id="single_rate-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="both_rate">Both rate <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('both_rate') }}" id="both_rate" name="both_rate" type="text" inputmode="decimal" value="{{ getValue('both_rate', $investigation, '0.00') }}" maxlength="13" required {{ inputAttrs('both_rate') }}>
                    @error('both_rate')<div class="invalid-feedback d-block" id="both_rate-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="room_no">Room no.</label>
                    <input class="{{ inputClass('room_no') }}" id="room_no" name="room_no" type="text" value="{{ getValue('room_no', $investigation) }}" maxlength="50" autocomplete="off" {{ inputAttrs('room_no') }}>
                    @error('room_no')<div class="invalid-feedback d-block" id="room_no-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="ordering">Ordering <span class="text-danger" aria-hidden="true">*</span></label>
                    <input class="{{ inputClass('ordering') }}" id="ordering" name="ordering" type="number" inputmode="numeric" value="{{ getValue('ordering', $investigation, 0) }}" min="0" max="65535" step="1" required {{ inputAttrs('ordering') }}>
                    @error('ordering')<div class="invalid-feedback d-block" id="ordering-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        @include('core::partials.form-actions', ['model' => $investigation, 'routePrefix' => $routePrefix, 'submitLabel' => $submitLabel])
    </form>
@endsection
