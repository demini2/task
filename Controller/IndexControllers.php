<?php

namespace Controller;

use Exception;
use Models\Task;
use Models\User;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Контроллер для просмотра всех новостей
 */
class IndexController extends Controller
{
    /**
     * получаем все новости
     * проверяем права доступа
     * если есть рисуем шаблон
     * @param array|null $params
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    protected function handle(?array $params): void
    {
        if (empty($_POST['email'])) {
            echo $this->environment->render('index.twig',
                ['flag' => false,]);
            return;
        }

        $user = new User();
        $users = $user->findUserByEmail(email: $_POST['email']);

        if (null === $users) {
            $newUser = new User();
            $newUser->setEmail(email: $_POST['email']);
            $newUser->insert();
            header(header: 'Location: ?=Tasks/action');
        }

        $_SESSION['userId'] = $users->getId();
        if (1 === $_SESSION['userId']) {
            if (require_once __DIR__ . '/../http-basic.php') {
                $allUsers = $user->findAllAndCountTask();
                if (empty($allUsers)) {
                    throw new Exception('Хюстон, у нас нет пользователей!');
                }
                $task = new Task();
                $users->setCountTask($task->countByAuthorId($user->getId()));
                echo $this->environment->render('index.twig',
                    [
                        'users' => $allUsers,
                        'flag' => true,
                    ]);
                return;
            }
        }
        header(header: 'Location: ?=Tasks/action');
    }
}
