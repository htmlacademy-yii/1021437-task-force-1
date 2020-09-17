<?php

namespace Task\classes;

class CancelAction extends AbstractAction
{

    public function getName()
    {
        return 'Отменить';
    }

    public function getPrivateName()
    {
        return 'cancel';
    }

    public function checkRule(int $user, int $executor, int $current_user): bool
    {
        return $user === $current_user ? true : false;
    }
}
