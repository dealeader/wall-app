<?php

namespace App\Http\Controllers;

use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        return MessageResource::collection(Message::all());
    }
}
