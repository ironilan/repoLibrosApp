<?php

namespace App\Http\Controllers\profesor;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $books = Folder::where('estado', 1)->where('user_id', $userId)->get();

        return response()->json($books);
    }
}
