<?php

class UserController
{
    public function index()
    {
        echo "Список пользователей";
    }

    public function show($id)
    {
        echo "Пользователь с ID: $id";
    }

    public function store()
    {
        echo "Создание пользователя";
    }

    public function update($id)
    {
        echo "Обновление пользователя с ID: $id";
    }

    public function destroy($id)
    {
        echo "Удаление пользователя с ID: $id";
    }
}