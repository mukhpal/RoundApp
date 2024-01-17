<?php


namespace App\Models;


class WebUser extends User
{

    public function getTable()
    {
        return "users";
    }
}
