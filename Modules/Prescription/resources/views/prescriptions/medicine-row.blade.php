@php
    $field = 'medicines.'.$medicineIndex;
    $name = 'medicines['.$medicineIndex.']';
    $errorId = str_replace('.', '-', $field);
    $controlClass = 'form-control form-control-sm';
    $doseValue = data_get($medicine, 'dose');
    $instructionValue = data_get($medicine, 'instructions');
    $instructionChoices = ['খাওয়ার পরে', 'খাওয়ার আগে', 'Chewable'];
    $productId = data_get($medicine, 'product_id');
    $product = $medicineProducts->get($productId);
    $genericName = data_get($product, 'category.name', '');
    $dosageType = data_get($medicine, 'dosage_type') ?? data_get($product, 'dosage.name', '');
@endphp

<li class="prescription-medicine-row d-flex flex-column mt-3" id="{{ $errorId }}-row">
    <div class="app-prescription-rx-card-heading d-flex align-items-center">
        <span class="prescription-medicine-number" aria-hidden="true"></span>
        <div class="flex-grow-1">
            <label class="app-prescription-medicine-name d-flex align-items-center">
                <span class="visually-hidden">Medicine</span>
                <input type="hidden" name="{{ $name }}[product_id]" form="prescription-form"
                    value="{{ $productId }}">
                <input name="{{ $name }}[medicine_name]" form="prescription-form" required maxlength="255"
                    class="{{ inputClass($field.'.medicine_name', $controlClass) }}"
                    value="{{ data_get($medicine, 'medicine_name') }}"
                    {{ inputAttrs($field.'.medicine_name') }}>
                @error($field.'.medicine_name')
                    <span class="invalid-feedback" id="{{ $errorId }}-medicine_name-error">
                        {{ $message }}
                    </span>
                @enderror
                @error($field.'.product_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </label>
            <div class="small text-body-secondary prescription-medicine-generic" hidden>
                {{ $genericName }}
            </div>
        </div>
        <button class="btn btn-outline-danger btn-sm prescription-medicine-remove" type="button"
            aria-label="Remove medicine">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <div class="app-prescription-rx-card-controls d-grid align-items-start">
        <div class="prescription-dosage-field">
            <label class="form-label" for="{{ $errorId }}-dosage-type">Type</label>
            <select class="{{ inputClass($field.'.dosage_type', 'form-select form-select-sm') }} prescription-dosage-type"
                id="{{ $errorId }}-dosage-type" name="{{ $name }}[dosage_type]" form="prescription-form"
                {{ inputAttrs($field.'.dosage_type') }}>
                @if (! filled($dosageType) || ! array_key_exists($dosageType, $dosageTypes))
                    <option value="{{ $dosageType }}" selected hidden>{{ $dosageType }}</option>
                @endif
                @foreach ($dosageTypes as $value => $label)
                    <option value="{{ $value }}" @selected($dosageType === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error($field.'.dosage_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="prescription-frequency-field d-grid align-content-start text-start">
            <label class="form-label prescription-frequency-label" for="{{ $errorId }}-frequency">Dose</label>
            <input id="{{ $errorId }}-frequency" name="{{ $name }}[frequency]" form="prescription-form"
                type="text" maxlength="100" placeholder="1+0+1" autocomplete="off"
                class="{{ inputClass($field.'.frequency', $controlClass) }} prescription-frequency-value"
                value="{{ data_get($medicine, 'frequency') }}" {{ inputAttrs($field.'.frequency') }}>
            @error($field.'.frequency')
                <div class="invalid-feedback d-block" id="{{ $errorId }}-frequency-error">{{ $message }}</div>
            @enderror
            <div class="prescription-application-dose" hidden>
                <div class="row g-2">
                    <div class="col-7">
                        <label class="form-label" for="{{ $errorId }}-application-amount">পরিমাণ</label>
                        <div class="input-group input-group-sm flex-nowrap">
                            <input class="form-control prescription-application-amount" type="text" inputmode="decimal"
                                id="{{ $errorId }}-application-amount" form="prescription-form" maxlength="30" autocomplete="off">
                            <span class="input-group-text prescription-application-unit">ফোঁটা করে</span>
                        </div>
                    </div>
                    <div class="col-5">
                        <label class="form-label" for="{{ $errorId }}-application-times">দিনে</label>
                        <div class="input-group input-group-sm flex-nowrap">
                            <input class="form-control prescription-application-times" type="text" inputmode="numeric"
                                id="{{ $errorId }}-application-times" form="prescription-form" maxlength="3" pattern="[0-9০-৯]+" autocomplete="off">
                            <span class="input-group-text">বার</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown prescription-schedule-menu">
                <button class="btn btn-sm dropdown-toggle prescription-frequency-mode" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false" value="3">Schedule</button>
                <ul class="dropdown-menu" role="menu">
                    @foreach ([3, 4, 5, 6] as $periodCount)
                        <li>
                            <button class="dropdown-item prescription-schedule-count" type="button"
                                role="menuitemradio" aria-checked="false"
                                value="{{ $periodCount }}">{{ $periodCount }}</button>
                        </li>
                    @endforeach
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button class="dropdown-item prescription-schedule-count" type="button"
                            role="menuitemradio" aria-checked="false" value="custom">Custom frequency</button>
                    </li>
                </ul>
            </div>
            <div class="prescription-schedule-controls" role="group" aria-label="Daily schedule"></div>
            <div class="app-prescription-food-options" role="group" aria-label="Food instructions">
                <button class="prescription-food-instruction" type="button" aria-pressed="false">
                    খাওয়ার পরে
                </button>
                <button class="prescription-food-instruction" type="button" aria-pressed="false" tabindex="-1">
                    খাওয়ার আগে
                </button>
            </div>
            <div class="prescription-instruction-selection" aria-live="polite"></div>
        </div>

        <div class="app-prescription-rx-card-right d-grid align-items-start text-start">
            <div class="prescription-dose-field">
                <label class="form-label" for="{{ $errorId }}-dose">Unit</label>
                <div class="dropdown prescription-unit-picker">
                    <button id="{{ $errorId }}-dose" type="button" value="{{ $doseValue }}"
                        class="{{ inputClass($field.'.dose', 'btn btn-sm dropdown-toggle prescription-unit-toggle') }}"
                        aria-expanded="false" aria-controls="prescription-unit-palette"
                        aria-keyshortcuts="F4" title="Unit (F4)"
                        {{ inputAttrs($field.'.dose') }}>
                        <span class="prescription-unit-label">{{ filled($doseValue) ? $doseValue : 'select' }}</span>
                    </button>
                </div>
                <input type="hidden" name="{{ $name }}[dose]" form="prescription-form" value="{{ $doseValue }}">
                @error($field.'.dose')
                    <div class="invalid-feedback d-block" id="{{ $errorId }}-dose-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="prescription-duration-field">
                <label class="form-label" for="{{ $errorId }}-duration-count">Duration</label>
                <div class="prescription-duration-controls d-flex align-items-center">
                    <input class="form-control form-control-sm prescription-duration-count" type="number"
                        id="{{ $errorId }}-duration-count" form="prescription-form" min="1" step="1">
                    <select class="form-select form-select-sm prescription-duration-unit" aria-label="Duration unit">
                        <option value="days">দিন</option>
                        <option value="weeks">সপ্তাহ</option>
                        <option value="months">মাস</option>
                        <option value="years">বছর</option>
                        <option value="continue">চলবে</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <label class="visually-hidden" for="{{ $errorId }}-duration">Custom duration</label>
                <input id="{{ $errorId }}-duration" name="{{ $name }}[duration]" form="prescription-form"
                    maxlength="100" hidden
                    class="{{ inputClass($field.'.duration', $controlClass) }} prescription-duration-value"
                    value="{{ data_get($medicine, 'duration') }}" {{ inputAttrs($field.'.duration') }}>
                @error($field.'.duration')
                    <div class="invalid-feedback d-block" id="{{ $errorId }}-duration-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="app-prescription-instruction-row w-100">
                <label class="visually-hidden" for="{{ $errorId }}-instruction-search">
                    Search medicine instructions
                </label>
                <select id="{{ $errorId }}-instruction-search"
                    class="{{ inputClass($field.'.instructions', 'form-select') }} prescription-instruction-search"
                    data-url="{{ route('prescription.options.selector', ['section' => 'medicine_instruction']) }}"
                    @if ($canCreateOptions) data-create-url="{{ route('prescription.options.store') }}" @endif
                    {{ inputAttrs($field.'.instructions') }}>
                    <option value=""></option>
                    @foreach ($instructionChoices as $choice)
                        <option value="{{ $choice }}" @selected($instructionValue === $choice)>{{ $choice }}</option>
                    @endforeach
                    @if (filled($instructionValue) && ! in_array($instructionValue, $instructionChoices, true))
                        <option value="{{ $instructionValue }}" selected>{{ $instructionValue }}</option>
                    @endif
                </select>
                <input type="hidden" name="{{ $name }}[instructions]" form="prescription-form"
                    value="{{ $instructionValue }}">
                <div @class([
                    'prescription-instruction-error',
                    'invalid-feedback d-block' => $errors->has($field.'.instructions'),
                ])
                    id="{{ $errorId }}-instructions-error" role="alert">
                    @error($field.'.instructions'){{ $message }}@enderror
                </div>
            </div>
        </div>
    </div>
</li>
