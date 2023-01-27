<?php

namespace Models;

use Exception;
use Generator;
use PDO;
use PDOStatement;

/**
 * класс устанавливающий связь с базой данных
 */
class Db
{
    /** @var string имя хоста */
    protected string $host;
    /** @var string логин */
    protected string $login;
    /** @var string пароль */
    protected string $password;
    /** @var PDO объект класса ПДО */
    protected PDO $pdo;

    /**
     * устанавливаем соннект с базой данных
     * если не удалось ексепшен
     * @throws Exception
     */
    public function __construct()
    {
        $config = new Config();

        $this->host = $config->getHost();
        $this->login = $config->getLogin();
        $this->password = $config->getPassword();
        $this->pdo = new PDO($this->host, $this->login, $this->password);
    }

    /**
     * принимаем sql запрос,
     * массив подстановки (по у молчанию пустой),
     * и класс в виде которого будет возврашен результат
     * в случае успеха вернется массив объектов
     * @param string $sql
     * @param string $class
     * @param array $data
     * @return array|null
     * @throws Exception
     */
    public function query(string $sql, string $class, array $data = []): ?object
    {
        $pdo = $this->pdo->prepare($sql);
        $pdo->execute($data);
        $result = $pdo->fetchObject($class);
        if (false === $result) {
            return null;
        }
        return $result;
    }

    /**
     * Вывот двнных через генератор
     *
     * @param string $sql
     * @param string $class
     * @param array $data
     * @return bool|array
     */
    public function fetchAll(string $sql, string $class, array $data = []): bool|array
    {
        $sth = $this->pdo->prepare(
            $sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute($data);
        return $sth->fetchAll(PDO::FETCH_CLASS, $class);

    }

    /**
     * принимаем sql запрос
     * и массив подстановки (по у молчанию пустой)
     * получим true в случае успеха
     *
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function execute(string $sql, array $params = []): bool
    {
        $sth = $this->pdo->prepare($sql);
        return $sth->execute($params);
    }

    /**
     * получаем последний записаны Id
     * @return int
     */
    public function getLastId(): int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Подготавливает оператор к выполнению и возвращает объект оператора
     *
     * @param $sql
     * @return bool|PDOStatement
     */
    public function getPrepare($sql): bool|\PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * Возвращает количество по sql запросу и параметрам
     * @param string $sql
     * @param array $params
     * @return int
     *
     */
    public function getCount(string $sql, array $params = []): int
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        return $query->rowCount();
    }

}