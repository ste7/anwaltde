<?php

namespace App\Observers;

class TestModelObserver
{
    public function saving()
    {
        return false;
    }
}
