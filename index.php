<?php
error_reporting(E_ALL);

use Task\handler\Task;

require_once 'vendor/autoload.php';

$newTask = new Task('1', '2', 1);

echo $newTask->getNextStatus('respond') . '<br>';

assert($newTask->getNextStatus('respond') == Task::STATUS_IN_WORK, 'Данные не совпадают');

echo $newTask->getActionsFromStatus('in_work') . '<br>';
