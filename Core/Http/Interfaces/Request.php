<?php
namespace Core\Http\Interfaces;

interface Request{

    // First method to retreive all the request params

    public function ready(): void;

    // Second method to validate all the request params

    public function dispatch(): void;

    // Third method to print all the request params validation errors or success message

    public function respond(): void;

}

?>