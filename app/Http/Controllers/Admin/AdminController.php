<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;
use App\Mail\WebsiteEmail;

class AdminController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }
}
