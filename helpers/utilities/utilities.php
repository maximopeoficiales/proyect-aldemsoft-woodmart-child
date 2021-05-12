<?php

use Rakit\Validation\Validator;

function adldem_UtilityValidator($data, $validations)
{
    $validator = new Validator;

    $validator->setMessage('required', "El campo :attribute es requerido");
    $validator->setMessage('numeric', "El campo :attribute debe ser un numero");
    $validator->setMessage('max', "El campo :attribute debe tener maximo de :max caracteres");
    $validator->setMessage('min', "El campo :attribute debe tener minimo de :min caracteres");
    $validator->setMessage('email', "El campo :attribute debe tener un formato de email");

    $validation = $validator->make($data, $validations);
    $validation->validate();
    if ($validation->fails()) {
        $errors = $validation->errors();
        $text = "";
        foreach ($errors->firstOfAll() as $key => $value) {
            $text .= strval($value) . ". -";
        }
        return ["validate" => false, "message" => $text];
    } else {
        return ["validate" => true];
    }
}

function aldem_cargarStyles(): void
{
    require aldem_get_directory_helper() . "public/styles.php";
}
