<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AudioBook;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use App\Models\SubscriptionOffer;
use App\Models\LoginScreenPicture;
use App\Models\SubscriptionSection;
use App\Http\Controllers\Controller;
use App\Responsables\V1\HomeResponse;
use App\Responsables\V1\ModelResponse;

class HomeController extends Controller
{
    /**
     * Get home sections.
     *
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $user->home_count += 1;
        if($user->isSubscribed() == false) {
            if($user->audioBooks()->where('status', AudioBook::STATUS_IN_PROGRESS)->whereNull('archived_at')->count() >= 1) {
                if($user->home_count % $user->display_rating_when == 0 && ($user->accepted_free_subscription == 0 && $user->home_count % $user->display_free_subscription_when == 0)) {
                    $homeSections = HomeSection::orderBy('order', 'asc');
                } elseif ($user->accepted_free_subscription == 0 && $user->home_count % $user->display_free_subscription_when == 0) {
                    $homeSections = HomeSection::where('is_rating', '!=', 1)->orderBy('order', 'asc');
                } elseif ($user->home_count % $user->display_rating_when == 0) {
                    $homeSections = HomeSection::where('is_free_subscription', '!=', 1)->orderBy('order', 'asc');
                } else {
                    $homeSections = HomeSection::where('is_free_subscription', '!=', 1)->where('is_rating', '!=', 1)->orderBy('order', 'asc');
                }
            } else {
                if ($user->home_count % $user->display_rating_when == 0 && ($user->accepted_free_subscription == 0 && $user->home_count % $user->display_free_subscription_when == 0)) {
                    $homeSections = HomeSection::where('template', '!=', 'currently_played')->orderBy('order', 'asc');
                } elseif ($user->accepted_free_subscription == 0 && $user->home_count % $user->display_free_subscription_when == 0) {
                    $homeSections = HomeSection::where('is_rating', '!=', 1)->where('template', '!=', 'currently_played')->orderBy('order', 'asc');
                } elseif ($user->home_count % $user->display_rating_when == 0) {
                    $homeSections = HomeSection::where('is_free_subscription', '!=', 1)->where('template', '!=', 'currently_played')->orderBy('order', 'asc');
                } else {
                    $homeSections = HomeSection::where('is_free_subscription', '!=', 1)->where('is_rating', '!=', 1)->where('template', '!=', 'currently_played')->orderBy('order', 'asc');
                }
            }
        } else {
            if($user->audioBooks()->where('status', AudioBook::STATUS_IN_PROGRESS)->whereNull('archived_at')->count() >= 1) {
                if($user->home_count % $user->display_rating_when == 0) {
                    $homeSections = HomeSection::where('is_free_subscription', '!=', 1)->orderBy('order', 'asc');
                } else {
                    $homeSections = HomeSection::where('is_rating', '!=', 1)->where('is_free_subscription', '!=', 1)->orderBy('order', 'asc');
                }
            } else {
                if($user->home_count % $user->display_rating_when == 0) {
                    $homeSections = HomeSection::where('template', '!=', 'currently_played')->where('is_free_subscription', '!=', 1)->orderBy('order', 'asc');
                } else {
                    $homeSections = HomeSection::where('is_rating', '!=', 1)->where('template', '!=', 'currently_played')->where('is_free_subscription', '!=', 1)->orderBy('order', 'asc');
                }
            }
        }
        $user->save();
    	return new HomeResponse($homeSections, true);
    }

    /**
     * Get Login Screen pictures.
     *
     * @return Illuminate\Http\Response
     */
    public function loginScreenPicture()
    {
        return new ModelResponse(LoginScreenPicture::orderBy('order', 'ASC'), true);
    }

    /**
     * Get subscription presentation offer.
     *
     * @return Illuminate\Http\Response
     */
    public function subcriptionOffer()
    {
        $subscriptionOffer = SubscriptionOffer::first();
        if ($subscriptionOffer) {
            return new ModelResponse($subscriptionOffer, false);
        } else {
            return response(null, 204);
        }
    }

    /**
     * Get subscription sections.
     *
     * @return Illuminate\Http\Response
     */
    public function subscriptionSection(Request $request)
    {
        $subscriptionSections = SubscriptionSection::orderBy('order', 'ASC');
        if ($subscriptionSections) {
            return new ModelResponse($subscriptionSections, true);
        } else {
            return response(null, 204);
        }
    }

}
