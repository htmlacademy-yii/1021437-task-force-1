<?php

namespace Task\classes;

class RespondAction extends AbstractAction
{

    public function getName()
    {
        return 'Откликнуться';
    }

    public function getPrivateName()
    {
        return 'respond';
    }

    public function checkRule(int $user, int $executor, int $current_user)
    {
        return $executor === $current_user ? true : false;
    }
}
