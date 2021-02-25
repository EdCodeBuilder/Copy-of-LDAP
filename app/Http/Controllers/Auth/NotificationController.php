<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        return $this->success_message(
            auth('api')->user()->notifications
        );
    }

    public function markAsRead($id)
    {
        $notification = auth('api')->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return $this->success_message(
            'Notificación leida'
        );
    }

    public function markAllAsRead()
    {
        auth('api')->user()->unreadNotifications->markAsRead();
        return $this->success_message(
            'Notificación marcadas como leidas'
        );
    }

    public function destroy($id)
    {
        auth('api')->user()->notifications()->where('id', $id)->delete();
        return $this->success_message(
            'Notificación eliminada'
        );
    }
}
