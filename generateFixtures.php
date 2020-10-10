<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use Task\classes\Task;
use Task\classes\exceptions\IncorrectStatusException;
use Task\classes\exceptions\IncorrectRoleException;
use Task\classes\exceptions\IncorrectActionException;
use Task\classes\exceptions\NoFileException;
use Task\classes\exceptions\EmptyFileException;
use Task\classes\utils\CsvConverter;

require_once 'vendor/autoload.php';

try {
    $newTask = new Task(1, 2, 2, 'in_work');
    var_dump($newTask->getActionsFromStatus());
    echo "<br>";
    var_dump($newTask->getNextStatus('respond'));
    echo "<br>";
    var_dump($newTask->getNextStatus('cancel'));
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

$fixtureList = [
    [
        'web/public/data/cities.csv',
        'migrations/dumpCities.sql',
        'city',
        ['city', 'latitude_y', 'longitude_x'],
        []
    ],
    [
        'web/public/data/categories.csv',
        'migrations/dumpCategories.sql',
        'category',
        ['name', 'category_icon'],
        []
    ],
    [
        'web/public/data/users.csv',
        'migrations/dumpUsers.sql',
        'user',
        ['email', 'name', 'password', 'registration_at', 'city_id'],
        [1108]
    ],
    [
        'web/public/data/profiles.csv',
        'migrations/dumpProfiles.sql',
        'profile',
        ['address', 'birthday_at', 'user_info', 'phone', 'skype', 'user_id'],
        [20]
    ],
    [
        'web/public/data/tasks.csv',
        'migrations/dumpTasks.sql',
        'task',
        [
            'created_at',
            'category_id',
            'description',
            'ends_at',
            'title',
            'address',
            'budget',
            'latitude_y',
            'longitude_x',
            'author_id'
        ],
        [20]
    ],
    [
        'web/public/data/replies.csv',
        'migrations/dumpReplies.sql',
        'response',
        ['created_at', 'budget', 'text_responses', 'task_id', 'executor_id'],
        [10, 20]
    ],
    [
        'web/public/data/opinions.csv',
        'migrations/dumpOpinions.sql',
        'feedback',
        ['created_at', 'rating', 'comment', 'task_id', 'executor_id', 'author_id'],
        [10, 20, 20]
    ]
];

file_put_contents('generalData.sql', '');

try {
    $dataSet = new CsvConverter();
    foreach ($fixtureList as $fixture) {
        $dataSet->import(
            $fixture[0],
            $fixture[1],
            $fixture[2],
            $fixture[3],
            $fixture[4]
        );
    }
} catch (NoFileException $e) {
    error_log("Ошибка при открытии файла: " . $e->getMessage());
    echo $e->getMessage();
} catch (EmptyFileException $e) {
    error_log('Ошибка при открытии файла: ' . $e->getMessage());
    echo $e->getMessage();
}
