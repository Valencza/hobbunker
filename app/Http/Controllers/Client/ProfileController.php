<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('client.pages.profile.index', compact(
            'user'
        ));
    }

    public function photo()
    {
        $user = Auth::user();

        return view('client.pages.profile.photo', compact(
            'user'
        ));
    }

    public function upload()
    {
        $images = request()->file('images');
        $number = request('number');

        foreach ($images as $image) {
            $fileName = $number . '.jpg';
            $image->move(public_path('labeled_images/' . Auth::user()->email), $fileName);
        }

        return response()->json(['message' => 'Images uploaded successfully']);
    }
}
