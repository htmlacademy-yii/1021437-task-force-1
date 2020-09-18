<?php

namespace Task\classes;

class RefuseAction extends AbstractAction
{

    public function getName()
    {
        return 'Отказаться';
    }

    public function getPrivateName()
    {
        return 'deny';
    }

    public function checkRule(int $user, int $executor, int $current_user)
    {
        return $current_user === $executor ? true : false;
    }
}
