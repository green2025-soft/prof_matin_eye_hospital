<?php

use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;

function commissionTypes(): array
{
    return ['iol' => 'IOL Commission', 'minor' => 'Minor Commission'];
}

function patientTypes(): array
{
    return ['new' => 'New', 'follow_up' => 'Follow Up'];
}

function eyeTypes(): array
{
    return ['left' => 'Left', 'right' => 'Right', 'both' => 'Both'];
}

function clinicalServiceOptions(): array
{
    return ['all' => 'All', 'consultation' => 'Consultation', 'investigation' => 'Investigation', 'operation' => 'Operation'];
}

function genders(): array
{
    return ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
}

function bloodGroups(): array
{
    return ['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-'];
}

function normalizeEmail(mixed $email): ?string
{
    return is_string($email) ? Str::lower(trim($email)) : null;
}

function inputClass(string $field, string $baseClass = 'form-control'): string
{
    $errors = session('errors');

    return $errors instanceof ViewErrorBag && $errors->has($field)
        ? $baseClass.' is-invalid'
        : $baseClass;
}

function inputAttrs(string $field, ?string $id = null, ?string $help = null): ComponentAttributeBag
{
    $attributes = [];
    $describedBy = $help;
    $errors = session('errors');

    if ($errors instanceof ViewErrorBag && $errors->has($field)) {
        $attributes['aria-invalid'] = 'true';
        $errorId = ($id ?? str_replace('.', '-', $field)).'-error';
        $describedBy = trim(($help ?? '').' '.$errorId);
    }

    if ($describedBy !== null && $describedBy !== '') {
        $attributes['aria-describedby'] = $describedBy;
    }

    return (new ComponentAttributeBag)->merge($attributes);
}

if (! function_exists('getValue')) {
    function getValue(string $key, object|array|null $objData = null, mixed $default = null): mixed
    {
        $defaultValue = data_get($objData, $key, $default);
        $value = old($key, $defaultValue);

        if (is_array($defaultValue)) {
            return is_array($value) ? $value : [];
        }

        return is_scalar($value) || $value === null ? $value : null;
    }
}

if (! function_exists('getSelected')) {
    function getSelected(int|string $optionValue, string $key, object|array|null $objData = null, mixed $default = null, string $attribute = 'selected'): string
    {
        foreach ((array) getValue($key, $objData, $default) as $value) {
            if (is_scalar($value) && (string) (is_bool($value) ? (int) $value : $value) === (string) $optionValue) {
                return $attribute;
            }
        }

        return '';
    }
}

if (! function_exists('getChecked')) {
    function getChecked(int|string $optionValue, string $key, object|array|null $objData = null, mixed $default = null): string
    {
        return getSelected($optionValue, $key, $objData, $default, 'checked');
    }
}
