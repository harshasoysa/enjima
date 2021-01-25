<?php
namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index($year, Request $request)
    {
        return new Response('Nope, this is not a leap year.');
    }
}