<?php
error_reporting(E_ALL);

use Task\handler\Task;

require_once 'vendor/autoload.php';

$newTask = new Task('1', '2', 0);

echo $newTask->getNextStatus('cancel') . '<br>';

assert($newTask->getNextStatus('failed') == Task::MAP_STATUSES_NAME[Task::STATUS_FAILED], 'Данные не совпадают');

echo $newTask->getActionsFromStatus('в работе') . '<br>';
