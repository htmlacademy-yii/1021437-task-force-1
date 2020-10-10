<?php
$this->title = 'Страница с именами пользователей - проверка';

    foreach ($users as $user) {
        echo $user . '<br>';
    }
    foreach ($profile['0'] as $element) {
        echo $element . '<br>';
    }
?>
