<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use JsonException;

class ContextCache
{
    public function isCacheable(): bool
    {
        if (app()->bound('request') && ! request()->isMethod('GET') && ! request()->isMethod('HEAD')) {
            return false;
        }

        foreach (DB::getConnections() as $connection) {
            if ($connection->transactionLevel() > 0) {
                return false;
            }
        }

        return true;
    }

    public function remember(string $connection, string $group, string|int $id, Closure $read): mixed
    {
        if (! $this->isCacheable()) {
            return $read();
        }

        $cache = Cache::store('context');
        $scope = $this->scope($connection, $group);
        $version = $cache->rememberForever($scope.'.version', fn (): string => bin2hex(random_bytes(16)));
        $key = $scope.'.entry.'.hash('sha256', (string) $id);
        $entry = $cache->get($key);

        if (is_array($entry) && ($entry['version'] ?? null) === $version && is_string($entry['value'] ?? null)) {
            try {
                return json_decode(Crypt::decryptString($entry['value']), true, flags: JSON_THROW_ON_ERROR);
            } catch (DecryptException|JsonException) {
                // A corrupt entry is replaced by a fresh database snapshot below.
            }
        }

        $value = $read();
        $cache->put($key, [
            'version' => $version,
            'value' => Crypt::encryptString(json_encode($value, JSON_THROW_ON_ERROR)),
        ], 900);

        return $value;
    }

    public function flush(string $connection, string $group): void
    {
        $database = DB::connection($connection);
        $cache = Cache::store('context');
        $key = $this->scope($connection, $group).'.version';

        // Capture the owning database before a later tenant switch. A new generation also
        // prevents an overlapping old read from repopulating the current cache after commit.
        $database->afterCommit(fn () => $cache->forever($key, bin2hex(random_bytes(16))));
    }

    private function scope(string $connection, string $group): string
    {
        $database = DB::connection($connection);

        return 'context.'.hash('sha256', $database->getName().'|'.$database->getDatabaseName().'|'.$group);
    }
}
