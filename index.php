<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use Task\classes\Task;
use Task\classes\exceptions\IncorrectStatusException;
use Task\classes\exceptions\IncorrectRoleException;
use Task\classes\exceptions\IncorrectActionException;

require_once 'vendor/autoload.php';

try {
    $newTask = new Task(1, 2, 2, 'in_work');
    var_dump($newTask->getActionsFromStatus());
    echo "<br>";
    var_dump($newTask->getNextStatus('respond'));
    echo "<br>";
    var_dump($newTask->getNextStatus('cancel2'));
} catch (IncorrectStatusException $e) {
    error_log('Ошибка в статусе: ' . $e->getMessage());
    echo 'Ошибка в статусе: ' . $e->getMessage();
} catch (IncorrectRoleException $e) {
    error_log('Ошибка в роли пользователя: ' . $e->getMessage());
    echo 'Ошибка в роли пользователя: ' . $e->getMessage();
} catch (IncorrectActionException $e) {
    error_log('Ошибка в указании действия: ' . $e->getMessage());
    echo 'Ошибка в указании действия: ' . $e->getMessage();
}
