<?php

namespace App\Http\Controllers;

use App\Events\UserMsgEvent;
use App\Events\MessageDeleteEvent;
use App\Events\MessageUpdateEvent;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;

class UserController extends Controller
{
    public function loadDashboard()
    {
        $data['users'] = User::whereNotIn('id', [auth()->user()->id])->get();
        return view('dashboard', $data);
    }

    public function saveChat(Request $request)
    {
        try {
            $chat = Chat::create([
                'sender_id' => $request->sendorId,
                'reciever_id' => $request->recieverId,
                'message' => $request->message,
            ]);

            event(new UserMsgEvent($chat));
            return response()->json(['success' => true, 'data' => $chat]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function loadChats(Request $request)
    {

        try {
            $chats = Chat::where(function ($query)  use ($request) {
                $query->where('sender_id', $request->sendorId)
                    ->orWhere('sender_id', $request->recieverId);
            })->where(function ($query)  use ($request) {
                $query->where('reciever_id', $request->sendorId)
                    ->orWhere('reciever_id', $request->recieverId);
            })->get();


            return response()->json(['success' => true, 'data' => $chats]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function deleteChat(Request $request)
    {
        try {
            Chat::where('id', $request->id)->delete();
            event(new MessageDeleteEvent($request->id));
            return response()->json(['success' => true, 'msg' => 'Chat Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function updateChat(Request $request)
    {
        try {
            Chat::where('id', $request->id)->update(['message' => $request->message]);
            $chat = Chat::where('id', $request->id)->first();
            event(new MessageUpdateEvent($chat));
            return response()->json(['success' => true, 'msg' => 'Chat Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
