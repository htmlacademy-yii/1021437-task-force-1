<?php

namespace Task\classes;

abstract class AbstractAction
{
    abstract public function getName();

    abstract public function getPrivateName();

    abstract public function checkRule(int $user, int $executor, int $current_user);
}
