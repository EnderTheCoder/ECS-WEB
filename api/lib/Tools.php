<?php

class Tools {
static function countX($array): int
{
    if (!$array) return 0;
    else return count($array);
}

//static function isEmpty($str): bool
//{
//    return !(!empty($str));
//}
static function checkFields($fields, $requiredList): bool {
    foreach ($requiredList as $key => $value) {
        if (empty($fields[$value])) return false;
    }
    return true;
}
}