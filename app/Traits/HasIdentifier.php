<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasIdentifier
{
    public function generateIdentifier(Model $model, int $size = 16, $limitSelection = false)
    {
        if ($limitSelection) {
            $selection = 'ABCDEFGHIJKLMNOPQRSTUVWXYS1234567890';
        } else {
            $selection = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYS1234567890';
        }

        $value = '';
        $idExists = false;

        do {
            //Generate ID
            for ($i = 0; $i < $size; $i++) {
                $value .= $selection[rand(0, strlen($selection) - 1)];
            }

            //Check if record exists with this ID
            if ($model->where('identifier', $value)->first()) {
                $idExists = true;
            } else {
                $idExists = false;
            }
        } while ($idExists);

        return $value;
    }
}
