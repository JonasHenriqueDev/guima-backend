<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    /**
     * Display the authenticated user.
     */
    public function me()
    {
        $user = Auth::user();

        return new MeResource($user);
    }
}
