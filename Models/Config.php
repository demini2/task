<?php

namespace models;
/**
 * конечный класс конфига
 */
final class Config
{
    /**
     * имя хоста
     * @var string
     */
    private string $host = 'mysql:host=localhost;dbname=test';

    /**
     * @var string логин
     */
    private string $login = 'root';
    /**
     * @var string пароль
     */
    private string $password = '';

    /**
     * получить хост
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * получить логин
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * получить пароль
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}