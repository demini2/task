<?php

namespace Models;

use Exception;

/**
 * класс предоставляет автора как объект
 */
class User extends Model
{
    /** константа названия таблицы */
    public const TABLE = 'users';

    /** @var int Id пользователя */
    public int $id;

    /** @var string Email пользователя */
    public string $email;

    /** @var int|null Количество задач */
    public ?int $task_count = null;

    /**
     * @throws Exception
     */
    public function findAllAndCountTask(): ?array
    {
        $sql = 'SELECT * (SELECT (*) FROM `tasks` WHERE `author_id` = `user`.`id`) AS `task_count` '. self::TABLE . ' `user`';
        return $this->findAll($sql, static::TABLE);
    }

    /**
     * ишем Id автора по имени
     * @param string $email
     * @return User|null
     * @throws Exception
     */
    public static function findUserByEmail(string $email): ?User
    {
        $db = new Db();
        $answer = $db->query(
            sql: 'SELECT * FROM ' . self::TABLE . ' WHERE email=:email',
            class: static::class,
            data: [':email' => $email]
        );
        if (empty($answer)) {
            return null;
        }

        return $answer;
    }

    /**
     * Получаем Id автора
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * получаем email автора
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param int|null $countTask
     */
    public function setCountTask(?int $countTask): void
    {
        $this->countTask = $countTask;
    }

    /**
     * @return int|null
     */
    public function getCountTask(): ?int
    {
        return $this->countTask;
    }
}