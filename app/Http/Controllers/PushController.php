<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = Auth::user();

        PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'user_id'    => $user->id,
                'public_key' => $request->keys['p256dh'],
                'auth_token' => $request->keys['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        PushSubscription::where('endpoint', $request->endpoint)->delete();
        return response()->json(['success' => true]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:100',
            'body'    => 'required|string|max:300',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        $pub  = config('services.vapid.public_key')  ?: env('VAPID_PUBLIC_KEY');
        $priv = config('services.vapid.private_key') ?: env('VAPID_PRIVATE_KEY');
        $subj = config('services.vapid.subject')     ?: env('APP_URL');

        \Log::info('VAPID debug', [
            'config_pub_len' => strlen((string) config('services.vapid.public_key')),
            'env_pub_len'    => strlen((string) env('VAPID_PUBLIC_KEY')),
            'pub_len'        => strlen((string) $pub),
            'subject'        => $subj,
            'all_env_keys'   => array_keys(array_filter(getenv(), fn($k) => str_starts_with($k, 'VAPID'), ARRAY_FILTER_USE_KEY)),
        ]);

        if (!$pub || !$priv) {
            return response()->json(['error' => 'VAPID keys not configured', 'pub' => (bool)$pub, 'priv' => (bool)$priv], 500);
        }

        $auth = [
            'VAPID' => [
                'subject'    => $subj,
                'publicKey'  => $pub,
                'privateKey' => $priv,
            ],
        ];

        $webPush = new WebPush($auth);

        $payload = json_encode([
            'title' => $request->title,
            'body'  => $request->body,
            'url'   => $request->url ?? '/',
        ]);

        $query = PushSubscription::query();
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        foreach ($query->get() as $sub) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint'        => $sub->endpoint,
                    'keys' => [
                        'p256dh' => $sub->public_key,
                        'auth'   => $sub->auth_token,
                    ],
                ]),
                $payload
            );
        }

        $results = [];
        foreach ($webPush->flush() as $report) {
            $results[] = [
                'endpoint' => $report->getRequest()->getUri(),
                'success'  => $report->isSuccess(),
            ];
            if ($report->isSubscriptionExpired()) {
                PushSubscription::where('endpoint', (string) $report->getRequest()->getUri())->delete();
            }
        }

        return response()->json(['success' => true, 'results' => $results]);
    }
}
