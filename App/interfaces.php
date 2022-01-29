<?php

interface addAble
{
    public function add(array $parameters): int;
}

interface deleteAble
{
    public function delete(int $columnId): bool;
}

interface updateAble
{
    public function update(array $parameters): bool;
}

interface getAble
{
    public function get(int $columnId = null);
}
