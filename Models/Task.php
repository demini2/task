<?php

namespace Models;

use PDO;

/**
 * Клас предосталяет задачу
 * в виде объекта
 */
class Task extends Model
{
    /** Название таблицы в БД*/
    public const TABLE = 'task';

    /** @var int Готовая задача */
    public const FLAG_READY = 3;

    /** @var int Важная задача */
    public const FLAG_IMPORTANT = 2;

    /** @var int Id новости */
    public int $id;

    /** @var int Id автора задания */
    public int $author_id;

    /** @var string Основной текст задания */
    public string $content;

    /** @var int|null Флаг задания */
    public ?int $flag;

    /** @var string Дата создания задания */
    public string $date_of_creation;

    /**
     * Возвращает время создания задачи
     *
     * @return string
     */
    public function getDateOfCreation(): string
    {
        return $this->date_of_creation;
    }

    /**
     * Устанавливает время создания задачи
     *
     * @param string $dateOfCreation
     */
    public function setDateOfCreation(string $dateOfCreation): void
    {
        $this->date_of_creation = $dateOfCreation;
    }

    /**
     * Устанавливает флаг
     *
     * @param int|null $flag
     */
    public function setFlag(?int $flag): void
    {
        $this->flag = $flag;
    }

    /**
     * Возвращает флаг
     *
     * @return int|null
     */
    public function getFlag(): ?int
    {
        return $this->flag;
    }

    /**
     * Устанавливает Id автора задачи
     *
     * @param int $authorTasksId
     */
    public function setAuthorTasksId(int $authorTasksId): void
    {
        $this->author_id = $authorTasksId;
    }


    /**
     * Устанавливает контент задачи
     *
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Получаем конттент задачи
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Получаем Id задачи
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получаем Id автора задачи
     *
     * @return string
     */
    public function getAuthorTasksId(): string
    {
        return $this->author_id;
    }

    /**
     * Вернуть количество записей по флагу
     *
     * @param int $flag
     * @return int
     */
    public function countByFlag(int $flag): int
    {
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE NOT flag =:flag ';
        return $this->countBySQL($sql, [':flag' => $flag]);
    }

    /**
     * Вернуть количество записей по флагу
     *
     * @param int $authorId
     * @return int
     */
    public function countByAuthorId(int $authorId): int
    {
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE author_id =:authorId ';
        return $this->countBySQL($sql, [':authorId' => $authorId]);
    }

    /**
     * Возвращает массив задач по указанным параметрам
     *
     * @param int $limit
     * @param int $offset
     * @param int $flag
     * @param string|null $orderBy
     * @return bool|array
     */
    public function findWithLimitOffsetOrderBy(int $limit, int $offset, int $flag, ?string $orderBy = null): bool|array
    {
        $sql = 'SELECT * FROM ' . static::TABLE;
        $sql .= ' WHERE flag !=:flag ';
        if ('' === $orderBy) {
            $sql .= ' ORDER BY id ASC';
        } else {
            $sql .= ' ORDER BY :orderBy, id DESC';
        }

        $sql .= ' LIMIT :limit OFFSET :offset';
        $query = $this->db->getPrepare($sql);
        if ('' !== $orderBy) {
            $query->bindParam(':orderBy', $orderBy);
        }
        $query->bindParam(':flag', $flag, PDO::PARAM_INT);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->setFetchMode(PDO::FETCH_CLASS, static::class);

        $query->execute();

        return $query->fetchAll();
    }
}
