<?php

namespace Controller;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use View\View;

/**
 * базовый класс контроллера
 * проверяет права доступа и рисует шаблон
 */
abstract class Controllers
{
    /** @var View $view */
    protected View $view;

    /**@var Environment */
    public Environment $environment;

    public function __construct()
    {
        $this->view = new View();
        $file = new FilesystemLoader('Display');
        $this->environment = new Environment($file);
    }

    /**
     * проверяем прова доступа
     * @return bool
     */
    protected function access(): bool
    {
        return true;
    }

    /**
     * выполняем права доступа
     * ресуем шаблон
     * или ексепшен
     * @param array|null $param
     * @return mixed
     * @throws Exception
     */
    public function action(?array $param): mixed
    {
        if ($this->access()) {
            return $this->handle($param);
        } else {
            throw new Exception('нет доступа');
        }
    }

    /**
     * Отображает экшен
     *
     * @return mixed
     */
    abstract protected function handle(?array $params);
}