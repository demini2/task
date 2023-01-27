<?php
declare(strict_types=1);

namespace Controller;

use Exception;
use Models\Task;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Контроллер для класса Task
 */
class TasksControllers extends Controllers
{
    /**
     * Главный экшен, выводит все задачи
     *
     * @param array|null $params
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function handle(?array $params): void
    {
        if (empty($_SESSION['userId'])) {
            header(header: 'Location: ?=Index/action');
        }

        $ts = new Task();

        $page = isset($params[1]) ? explode('=', $params[1])[1] : 1;
        $orderBy = 'date_of_creation';
        $limit = 3;
        $offset = ($page - 1) * $limit;
        $tasks = $ts->findWithLimitOffsetOrderBy(
            limit: $limit,
            offset: $offset,
            flag: Task::FLAG_READY,
            orderBy: $orderBy,
        );

        echo $this->environment->render(name: 'tasks.twig',
            context: [
                'currentPage' => $page,
                'currentFilters' => $tasks,
                'showAlwaysFirstAndLast ' => false,
                'lastPage' => ceil($ts->countByFlag(Task::FLAG_READY) / $limit),
                'paginationPath' => '?=Tasks/action&page=',
                'userId' => $_SESSION['userId'],
            ]);
    }


    /**
     * Экшен создания новой задачи
     *
     * @return void
     * @throws Exception
     */
    public function create(): void
    {

        if (
            !empty($_SESSION['userId']) &&
            !empty($_POST['content']) &&
            !empty($_POST['dateOfCreation'])
        ) {
            $tasks = new Task();
            $tasks->setAuthorTasksId($_SESSION['userId']);
            $tasks->setContent($_POST['content']);
            $tasks->setFlag((int)$_POST['flag'] ?? 1);
            $tasks->setDateOfCreation($_POST['dateOfCreation']);

            if ($tasks->save()) {
                echo("Задача была создана.");
            } else {
                echo("Невозможно создать задачу.");
            }
        } else {
            echo("Невозможно создать задачу. Данные неполные.");
        }
    }

    /**
     * Экшен проставления флага готовая задача
     *
     * @return string
     * @throws Exception
     */
    public function ready(): string
    {
        if (!empty($_SESSION['userId'] && $_POST["taskId"])) {
            $task = new Task();
            /** @var Task $readyTask */
            $readyTask = $task->findById($_POST["taskId"]);
            $readyTask->setFlag(Task::FLAG_READY);
            if ($readyTask->save()) {
                return 'Успешно отмечено выполнение задания';
            }
            return 'Не удалось поставить отметку об успешном выполнении taskId:' . $_POST["taskId"];
        }
        return 'не удалось получить taskId';
    }

    /**
     * Экшен редактирования задачи
     *
     */
    public function edit(): string
    {
        if (!empty($_SESSION['userId'] && $_POST['content'])) {
            $task =  new Task();
            $task->setContent($_POST['content']);
            if ($task->save()){

                return 'Задача успешно отредактирована';
            }
            return 'Не удалось отредактировать задачу taskId:' . $_POST["taskId"];
        }
        return 'не удалось получить content';

    }

    /**
     * Экшен удаления задачи
     *
     * @return string
     * @throws Exception
     */
    public function delete(): string
    {
        if (!empty($_SESSION['userId'] && $_POST["taskId"])) {
            $task = new Task();
            /** @var Task $readyTask */
            $readyTask = $task->findById($_POST["taskId"]);

            if ($readyTask->delete()) {
                return 'Задача успешно удалена';
            }
            return 'Не удалось удалить задачу taskId:' . $_POST["taskId"];
        }
        return 'не удалось получить taskId';
    }
}