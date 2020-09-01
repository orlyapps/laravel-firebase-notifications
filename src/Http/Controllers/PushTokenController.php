<?php

namespace Orlyapps\LaravelFirebaseNotifications\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Orlyapps\LaravelFirebaseNotifications\Http\Resources\PushTokenResource;
use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;

class PushTokenController
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $previous = PushToken::where('token', $request->token)->first();
        if ($previous !== null && $previous->user_id !== $request->user()->id) {
            $previous->delete();
        }
        $token = PushToken::firstOrCreate([
            'user_id' => $request->user()->id,
            'token' => $request->token,
        ], [
            'type' => $request->type,
        ]);

        return new PushTokenResource($token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PushToken  $pushToken
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushToken $pushToken, Request $request)
    {
        $this->authorize('delete', $pushToken);

        PushToken::where('user_id', $request->user()->id)
            ->where('token', $pushToken->token)
            ->delete();
    }
}
