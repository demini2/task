<?php

namespace Models;

use Exception;

/**
 *
 * исходный базавый класс
 */
abstract class Model
{
    /** константа названия таблицы*/
    public const TABLE = '';

    /** @var int Id */
    public int $id;

    public Db $db;

    /**
     *
     */
    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * получаем все записи
     * @return array|null
     * @throws Exception
     */
    public function findAll(string $sql, string $class, array $params = []): ?array
    {

        $arr = $this->db->fetchAll($sql,
            static::class,
            $params,
        );

        if (empty($arr)) {
            return null;
        }

        return $arr;
    }

    /**
     * находим записи по Id
     * @param string $id
     * @return object|null
     * @throws Exception
     */
    public function findById(string $id): ?object
    {

        $answer = $this->db->query(
            'SELECT * FROM ' . static::TABLE . ' WHERE id=:id ',
            static::class,
            ['id' => $id]
        );

        if (empty($answer)) {
            return null;
        }

        return $answer;
    }


    /**
     * получаем данные,
     * записываем в массив,
     * передаем sql запрос в базу
     * @return bool
     */
    public function insert(): bool
    {
        $fields = get_object_vars($this);
        $cols = [];
        $data = [];

        foreach ($fields as $name => $value) {
            if ('id' === $name || 'db' === $name) {
                continue;
            }
            $cols[] = $name;
            $data[':' . $name] = $value;
        }

        $sql = 'INSERT INTO ' . static::TABLE . '
        (' . implode(',', $cols) . ')
        VALUES 
        (' . implode(',', array_keys($data)) . ')';

        $ret = $this->db->execute($sql, $data);
        $this->id = $this->db->getLastId();
        return $ret;
    }

    /**
     * удаляем конкретную новость
     * по Id
     * @return bool
     */
    public function delete(): bool
    {
        $data = [':id' => $this->id];
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE id=:id ';
        return $this->db->execute($sql, $data);
    }

    /**
     * обновление данных в поле
     * по данному Id
     */
    public function update(): bool
    {
        $fields = get_object_vars($this);

        $cols = [];
        $data = [];

        foreach ($fields as $name => $value) {
            if ('id' === $name || 'db' === $name) {
                continue;
            }
            $cols[] = $name . ' = :' . $name;
            $data[':' . $name] = $value;
        }

        $sql = 'UPDATE ' . static::TABLE . ' SET ' .
            implode(', ', $cols) .
            ' WHERE id = ' . $this->id;

        return $this->db->execute($sql, $data);

    }

    /**
     * определяем какую функцию вызывать,
     * обновление, запись
     * @return bool
     */
    public function save(): bool
    {
        if (isset($this->id)) {

            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Получить общее количество записей по SQL запросу
     *
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function countBySQL(string $sql, array $params = []): int
    {
        return $this->db->getCount($sql, $params);
    }
}