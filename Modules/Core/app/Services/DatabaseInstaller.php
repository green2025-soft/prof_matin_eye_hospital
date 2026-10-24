<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Str;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseInstaller
{
    private const METADATA_MIGRATION = 'Modules/Core/database/migrations/shared/2026_10_24_132300_create_database_metadata_table.php';

    public function __construct(
        private DatabaseManager $database,
        private Kernel $console,
        private Repository $config,
    ) {}

    public function create(string $database): bool
    {
        $this->validateIdentifier($database);
        $connection = $this->database->connection('provisioning');

        if ($connection->selectOne('SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?', [$database], false)) {
            return false;
        }

        $charset = $connection->getConfig('charset');
        $collation = $connection->getConfig('collation');
        $this->validateIdentifier($charset);
        $this->validateIdentifier($collation);
        $connection->statement('CREATE DATABASE `'.$database.'` CHARACTER SET '.$charset.' COLLATE '.$collation);

        return true;
    }

    public function locked(string $database, Closure $callback): mixed
    {
        $this->validateIdentifier($database);
        $connection = $this->database->connection('provisioning');
        $key = hash('sha256', 'database:'.$database);
        $lock = $connection->selectOne('SELECT GET_LOCK(?, 30) AS acquired', [$key], false);

        if ((int) ($lock?->acquired) !== 1) {
            throw new RuntimeException('The database is busy. Please try again later.');
        }

        try {
            return $callback();
        } finally {
            $connection->selectOne('SELECT RELEASE_LOCK(?) AS released', [$key], false);
        }
    }

    public function claim(string $connection, #[\SensitiveParameter] string $token, bool $allowExisting = false): void
    {
        $hash = $this->ownershipHash($token);
        $target = $this->database->connection($connection);

        if ($target->getSchemaBuilder()->hasTable('database_metadata')) {
            $this->verify($connection, $token);

            return;
        }

        $tables = $target->selectOne(
            'SELECT COUNT(*) AS table_count FROM information_schema.tables WHERE table_schema = ?',
            [$target->getDatabaseName()],
            false,
        );

        if (! $allowExisting && (int) $tables->table_count > 0) {
            throw new RuntimeException('An existing unowned database requires explicit adoption.');
        }

        $this->runMigrations($connection, [base_path(self::METADATA_MIGRATION)]);
        $target->table('database_metadata')->insert([
            'id' => 1,
            'token' => $token,
            'platform_hash' => $hash,
            'created_at' => now(),
        ]);

        $this->verify($connection, $token);
    }

    public function verify(string $connection, #[\SensitiveParameter] string $token): void
    {
        $hash = $this->ownershipHash($token);
        $target = $this->database->connection($connection);

        if (! $target->getSchemaBuilder()->hasTable('database_metadata')) {
            throw new RuntimeException('Database ownership could not be verified.');
        }

        $markers = $target->table('database_metadata')->useWritePdo()->limit(2)->get(['id', 'token', 'platform_hash']);
        $marker = $markers->first();

        if ($markers->count() !== 1 || (int) $marker->id !== 1 ||
            ! hash_equals($token, (string) $marker->token) ||
            ! hash_equals($hash, (string) $marker->platform_hash)) {
            throw new RuntimeException('Database ownership could not be verified.');
        }
    }

    // Callers must hold the database lock and verify ownership before migrating.
    public function migrate(string $connection, bool $pretend = false, ?OutputInterface $output = null): void
    {
        $paths = $this->config->get('multitenancy.migrations.'.$connection);

        if (! is_array($paths) || $paths === []) {
            throw new LogicException('Migration paths are not configured for this connection.');
        }

        if ($pretend && ! $this->database->connection($connection)->getSchemaBuilder()->hasTable(
            $this->config->get('database.migrations.table', 'migrations'),
        )) {
            throw new RuntimeException('Claim the database before previewing migrations.');
        }

        $this->runMigrations($connection, $paths, $pretend, $output);
    }

    private function runMigrations(string $connection, array $paths, bool $pretend = false, ?OutputInterface $output = null): void
    {
        foreach ($paths as $path) {
            if (! is_string($path) || (! is_file($path) && ! is_dir($path))) {
                throw new LogicException('A configured migration path does not exist.');
            }
        }

        $status = $this->console->call('migrate', [
            '--database' => $connection,
            '--path' => $paths,
            '--realpath' => true,
            '--force' => true,
            '--pretend' => $pretend,
        ], $output);

        if ($status !== 0) {
            throw new RuntimeException('Database migration failed. Review the command output.');
        }
    }

    private function validateIdentifier(string $identifier): void
    {
        if (! preg_match('/\A[A-Za-z0-9_]{1,64}\z/', $identifier)) {
            throw new InvalidArgumentException('Database identifiers must contain 1 to 64 ASCII letters, digits, or underscores.');
        }
    }

    private function ownershipHash(#[\SensitiveParameter] string $token): string
    {
        if ($token !== 'central' && ! Str::isUuid($token)) {
            throw new InvalidArgumentException('A central or tenant ownership token is required.');
        }

        $key = $this->config->get('app.key');

        if (! is_string($key) || $key === '') {
            throw new LogicException('Configure the application key before claiming a database.');
        }

        return hash_hmac('sha256', $token, $key);
    }
}
