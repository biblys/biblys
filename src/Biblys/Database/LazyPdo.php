<?php
/*
 * Copyright (C) 2026 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Database;

use Biblys\Service\LoggerService;
use PDO;
use PDOException;
use PDOStatement;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

/**
 * A PDO subclass that defers opening the MySQL connection until it is first
 * used. Requests that never touch the legacy `$_SQL` connection (e.g. pages
 * served entirely through Propel) therefore no longer open a second MySQL
 * connection, which halves the concurrent connection count under load.
 *
 * The object IS a real PDO instance, so it satisfies existing `PDO` type
 * hints and requires no change to the ~80 legacy call sites that use
 * `global $_SQL`.
 */
class LazyPdo extends PDO
{
    private bool $connected = false;

    public function __construct(
        private readonly string  $dsn,
        private readonly ?string $username,
        private readonly ?string $password,
        private readonly string  $serverDescription,
    ) {
        // Intentionally do NOT call parent::__construct(): the actual MySQL
        // connection is opened lazily by ensureConnected() on first use.
    }

    /**
     * Opens the underlying connection on first use. Idempotent.
     *
     * Not named connect() to avoid colliding with PDO::connect(), the static
     * factory added in PHP 8.4 (a static/instance clash is a fatal error).
     *
     * @throws ServiceUnavailableHttpException when the connection cannot be opened.
     */
    private function ensureConnected(): void
    {
        if ($this->connected) {
            return;
        }

        try {
            parent::__construct($this->dsn, $this->username, $this->password);
        } catch (PDOException $exception) {
            $logger = new LoggerService();
            $logger->log(
                logger: "errors",
                level: "ERROR",
                message: "Cannot connect to MySQL server " . $this->serverDescription
                    . " #" . $exception->getCode() . ": " . $exception->getMessage(),
            );
            throw new ServiceUnavailableHttpException(null, "Une erreur est survenue lors de la connexion à la base de données. Consultez les logs pour plus de détails.");
        }

        $this->connected = true;
        parent::exec("SET CHARACTER SET utf8");
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->ensureConnected();
        return parent::prepare($query, $options);
    }

    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false
    {
        $this->ensureConnected();
        return parent::query($query, $fetchMode, ...$fetchModeArgs);
    }

    public function exec(string $statement): int|false
    {
        $this->ensureConnected();
        return parent::exec($statement);
    }

    public function beginTransaction(): bool
    {
        $this->ensureConnected();
        return parent::beginTransaction();
    }

    public function commit(): bool
    {
        $this->ensureConnected();
        return parent::commit();
    }

    public function rollBack(): bool
    {
        $this->ensureConnected();
        return parent::rollBack();
    }

    public function inTransaction(): bool
    {
        $this->ensureConnected();
        return parent::inTransaction();
    }

    public function lastInsertId(?string $name = null): string|false
    {
        $this->ensureConnected();
        return parent::lastInsertId($name);
    }

    public function quote(string $string, int $type = PDO::PARAM_STR): string|false
    {
        $this->ensureConnected();
        return parent::quote($string, $type);
    }

    public function errorCode(): ?string
    {
        $this->ensureConnected();
        return parent::errorCode();
    }

    public function errorInfo(): array
    {
        $this->ensureConnected();
        return parent::errorInfo();
    }

    public function getAttribute(int $attribute): mixed
    {
        $this->ensureConnected();
        return parent::getAttribute($attribute);
    }

    public function setAttribute(int $attribute, mixed $value): bool
    {
        $this->ensureConnected();
        return parent::setAttribute($attribute, $value);
    }
}
