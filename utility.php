<?php

/* Ritorna true se la password é valida (minimo 8 caratteri, 1 maiuscola, 1 numero
ed un carattere speciale). Ritorna false altrimenti */
function checkPassword($password) {
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    if (!preg_match('/[\W_]/', $password)) {
        return false;
    }
    return true;
}

/* Normalizza una stringa per farla diventare un identificatore JS valido */
function normalizeToIdentifier($string) {
    $accentMap = [
        'à' => 'a', 'á' => 'a', 'è' => 'e', 'é' => 'e', 
        'ì' => 'i', 'í' => 'i', 'ò' => 'o', 'ó' => 'o', 
        'ù' => 'u', 'ú' => 'u', 
        'À' => 'A', 'Á' => 'A', 'È' => 'E', 'É' => 'E', 
        'Ì' => 'I', 'Í' => 'I', 'Ò' => 'O', 'Ó' => 'O', 
        'Ù' => 'U', 'Ú' => 'U'
    ];
    $string = str_replace("'", "", $string);
    $string = strtr($string, $accentMap);
    $string = strtolower($string);
    $string = str_replace(' ', '_', $string);
    if (!empty($string) && !preg_match('/^[a-zA-Z_]/', $string)) {
        $string = '_' . $string;
    }
    return $string;
}

function hasUserCommented($comments, $nickname) {
    foreach ($comments as $comm) {
        if ($comm['nicknameValutatore'] == $nickname) {
            return true;
        }
    }
    return false;
}


?>