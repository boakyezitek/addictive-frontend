<?php

namespace App\Http\Controllers\Api\V1;

use RevenueCat;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RevenueCatController extends Controller
{
    /**
     * Get offerings.
     *
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $offerings = RevenueCat::getOffering($user->id);
    }

    /**
     * Webhook API
     *
     * @return Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        if ($request->event['type'] == "TEST") {
            return response()->json(['message' => 'success'], 200);
        } elseif ($request->event['type'] == "INITIAL_PURCHASE") {
            $user = User::where('hash_id', $request->event['app_user_id'])->first();
            if ($request->event['price_in_purchased_currency'] == 0 || $request->event['period_type'] == 'INTRO') {
                $user->intro_subscription_used = 1;
                $user->save();
            }
            $product_id_array = explode('.', $request->event['product_id']);
            $subscription = new Subscription([
                'user_id' => $user->id,
                'reference' => $request->event['product_id'],
                'interval' => end($product_id_array),
                'period_type' => $request->event['period_type'],
                'transaction_id' => $request->event['transaction_id'],
                'price' => $request->event['price_in_purchased_currency'],
                'currency' => $request->event['currency'],
                'purchased_at' => Carbon::createFromTimestampMs($request->event['purchased_at_ms']),
                'expiration_at' => Carbon::createFromTimestampMs($request->event['expiration_at_ms']),
                'status' => Subscription::STATUS_IN_PROGRESS,
                'platform' => $request->event['store'] == 'PLAY_STORE' ? 'android' : 'ios',
            ]);
            $subscription->save();
            return response()->json(['message' => 'success'], 200);
        } elseif ($request->event['type'] == "RENEWAL") {
            $user = User::where('hash_id', $request->event['app_user_id'])->first();
            $product_id_array = explode('.', $request->event['product_id']);
            $subscription = Subscription::where('transaction_id', $request->event['original_transaction_id'])->first();
            if ($subscription) {
                $old_price = $subscription->price;
                $subscription->expiration_at = Carbon::createFromTimestampMs($request->event['expiration_at_ms']);
                $subscription->renewed_at = Carbon::createFromTimestampMs($request->event['purchased_at_ms']);
                $subscription->renewed_count += 1;
                $subscription->period_type = $request->event['period_type'];
                $subscription->price = $request->event['price_in_purchased_currency'];
                $subscription->platform = $request->event['store'] == 'PLAY_STORE' ? 'android' : 'ios';
                $subscription->product_id = $request->event['product_id'];
                $subscription->save();
                if ($old_price != 0) {
                    $subscription->createCredits();
                }
                return response()->json(['message' => 'success'], 200);
            } else {
                $subscription = new Subscription([
                    'user_id' => $user->id,
                    'reference' => $request->event['product_id'],
                    'interval' => end($product_id_array),
                    'period_type' => $request->event['period_type'],
                    'transaction_id' => $request->event['original_transaction_id'],
                    'price' => $request->event['price_in_purchased_currency'],
                    'currency' => $request->event['currency'],
                    'purchased_at' => Carbon::createFromTimestampMs($request->event['purchased_at_ms']),
                    'expiration_at' => Carbon::createFromTimestampMs($request->event['expiration_at_ms']),
                    'status' => Subscription::STATUS_IN_PROGRESS,
                    'platform' => $request->event['store'] == 'PLAY_STORE' ? 'android' : 'ios',
                ]);
                $subscription->save();
                return response()->json(['message' => 'success'], 200);
            }
        } elseif ($request->event['type'] == "CANCELLATION") {
            $user = User::where('hash_id', $request->event['app_user_id'])->first();
            $subscription = Subscription::where('transaction_id', $request->event['original_transaction_id'])->first();
            if ($subscription) {
                $subscription->cancelled_at = Carbon::createFromTimestampMs($request->event['event_timestamp_ms']);
                $subscription->status = Subscription::STATUS_CANCELED;
                $subscription->save();
                return response()->json(['message' => 'success'], 200);
            }
        }
    }
}
