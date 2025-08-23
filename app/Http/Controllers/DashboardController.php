<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function __construct()
    {
        // pastikan hanya user yang sudah login bisa akses
        $this->middleware('auth');
    }
    public function index()
    {
        $data['title'] = 'Dashboard';

        return
            view('layouts.header', $data) .
            view('dashboard', $data) .
            view('layouts.footer');
    }
}
