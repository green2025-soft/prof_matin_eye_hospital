<?php

namespace Modules\Hospital\Services;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Arr;
use Modules\Core\Services\CachedUserProvider;

class HospitalUserProvider extends CachedUserProvider
{
    public function __construct(Hasher $hasher, string $userModel, private string $doctorModel)
    {
        parent::__construct($hasher, $userModel);
    }

    public function retrieveById($identifier)
    {
        $identity = $this->identity($identifier);
        if ($identity === null) {
            return null;
        }

        $provider = new CachedUserProvider($this->hasher, $identity['model']);

        return $provider->retrieveById($identity['id']);
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token)
    {
        $identity = $this->identity($identifier);
        if ($identity === null) {
            return null;
        }

        $provider = new EloquentUserProvider($this->hasher, $identity['model']);

        return $provider->retrieveByToken($identity['id'], $token);
    }

    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials)
    {
        $accountType = $credentials['account_type'] ?? 'user';
        if (! in_array($accountType, ['user', 'doctor'], true)) {
            return null;
        }

        $model = $accountType === 'doctor' ? $this->doctorModel : $this->model;

        return (new EloquentUserProvider($this->hasher, $model))->retrieveByCredentials(Arr::only($credentials, ['email', 'password']));
    }

    private function identity(mixed $identifier): ?array
    {
        if ((is_int($identifier) || is_string($identifier)) && preg_match('/\A[1-9][0-9]*\z/', (string) $identifier) === 1) {
            return ['model' => $this->model, 'id' => (string) $identifier];
        }

        if (is_string($identifier) && preg_match('/\Adoctor:([1-9][0-9]*)\z/', $identifier, $matches) === 1) {
            return ['model' => $this->doctorModel, 'id' => $matches[1]];
        }

        return null;
    }
}
