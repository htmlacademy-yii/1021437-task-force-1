<?php

namespace Task\classes\actions;

abstract class AbstractAction
{
    abstract public function getName(): string;

    abstract public function getPrivateName(): string;

    abstract public function checkRule(int $user, int $executor, int $currentUser): bool;
}
