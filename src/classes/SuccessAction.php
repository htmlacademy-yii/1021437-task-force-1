<?php

namespace Task\classes;

class SuccessAction extends AbstractAction
{

    public function getName()
    {
        return 'Выполнено';
    }

    public function getPrivateName()
    {
        return 'done';
    }

    public function checkRule(int $user, int $executor, int $current_user)
    {
        return $current_user === $user ? true : false;
    }
}
