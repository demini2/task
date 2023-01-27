<?php

namespace View;

use Exception;

/**
 * класс отображения шаблонов
 */
class View
{
    /**
     * @var array массив свойств и значений
     */
    private array $arrayContent = [];

    /**
     * @param string $name
     * @param array $values
     * @return void
     */
    public function __set(string $name, array $values): void
    {
        $this->arrayContent[$name] = $values;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function __get(string $name): ?array
    {
        return $this->arrayContent[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->arrayContent[$name]);
    }

    /**
     * буфируем шаблон
     *
     * @param string $template
     * @param array $arr
     * @return string
     * @throws Exception
     */
    private function render(string $template, array $arr=[]): string
    {
        $wey = __DIR__ . '/../Display/' . $template . '.twig';
        ob_clean();
        include $wey;
        $content = ob_get_contents();
        ob_end_clean();
        if (empty($content)) {
            throw new Exception('нет контента');
        }
        return $content;
    }

    /**
     * выводим шаблон на экран через буфер
     * @param string $template
     * @param array $arr
     * @return void
     * @throws Exception
     */
    public function display(string $template, array $arr=[]): void
    {
        echo $this->render($template,$arr);
    }
}