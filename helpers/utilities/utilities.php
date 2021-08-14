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


function aldem_getRoleCodes()
{
    return [
        ["id" => 41, "name" => "(41) Despachador de aduanas"],
        ["id" => 31, "name" => "(31) Depósito temporal, Zed"],
        ["id" => 33, "name" => "(33) Depósito temporal EER"],
        ["id" => 32, "name" => "(32) Depósito aduanero"],
        ["id" => 34, "name" => "(34) Depósito temporal Postal"],
        ["id" => 50, "name" => "(50) Empresa EER"],
        ["id" => 61, "name" => "(61) Terminal Portuario"],
        ["id" => 54, "name" => "(54) Terminal de carga aéreo"],
        ["id" => 73, "name" => "(73) Zofratacna"],
    ];
}


function aldem_selectRoleCodes($nameInput = "roleCode", $rolCodeDefault = 31)
{
    $html = "
    <div class='form-group'>
    <label for='${nameInput}'>Role Code</label>
    <select class='form-control' name='${nameInput}' id='${nameInput}'>
    ";
    foreach (aldem_getRoleCodes() as $roleCode) {
        $selected = $roleCode["id"] === $rolCodeDefault ? " selected" : "";
        $html .= "<option value='{$roleCode['id']}' $selected>
        {$roleCode['name']}        
        </option>";
    }
    $html .= "    
    </select> 
    </div>";
    echo $html;
}
