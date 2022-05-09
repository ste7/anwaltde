<?php

namespace App;

interface SearchRepository
{
    public function search($search, $perPage, $from);

    public function filter($field, $value);
}
