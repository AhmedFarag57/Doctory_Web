<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function indexById()
    {
        $user = auth()->user();

        $notifications = Notification::where('user_id', $user->id)->get();

        return $this->success($notifications);
    }
}
