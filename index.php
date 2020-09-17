<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Task\classes\Task;

require_once 'vendor/autoload.php';

$newTask = new Task('1', '2', 2);
$newTask2 = new Task('1', '2', 1);
$newTask3 = new Task('1', '2', 3);
$newTask4 = new Task('1', '2', 2);
$newTask5 = new Task('1', '2', 1);

echo $newTask->getNextStatus('respond') . '<br>';

assert($newTask->getNextStatus('respond') == Task::STATUS_IN_WORK, 'Данные не совпадают');

var_dump($newTask->getActionsFromStatus('in_work'));
echo "<br>";
var_dump($newTask2->getActionsFromStatus('new'));
echo "<br>";
var_dump($newTask3->getActionsFromStatus('in_work'));
echo "<br>";
var_dump($newTask4->getActionsFromStatus('new'));
echo "<br>";
var_dump($newTask5->getActionsFromStatus('new'));
