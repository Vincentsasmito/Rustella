<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class VerifyNoticeController extends Controller
{
    public function __invoke()
    {
        return view('auth.verify');
    }
}
