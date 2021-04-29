<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $books1 = array(
            array(
                'title' =>  'Nouveautés',
                'img' =>  'book-1'
            ),

            array(
                'title' =>  'Nouveautés',
                'img'   =>  'book-2'
            ),

            array(
                'title' =>  'Nouveautés',
                'img' =>   'book-3'
            ),

            array(
                'title' =>  'Nouveautés',
                'img'   =>  'book-4'
            ),

            array(
                'title' =>  'Nouveautés',
                'img' =>  'book-5'
            ),

            array(
                'title' =>  'Nouveautés',
                'img'   =>  'book-6'
            ),

            array(
                'title' =>  'Nouveautés',
                'img' =>  'book-7'
            ),

            array(
                'title' =>  'Nouveautés',
                'img'   =>  'book-8'
            )
        );
        $books2 = array(
            array(
                'title' =>  'À paraître',
                'img' =>  'book-9'
            ),

            array(
                'title' =>  'À paraître',
                'img'   =>  'book-10'
            ),

            array(
                'title' =>  'À paraître',
                'img' =>   'book-11'
            ),

            array(
                'title' =>  'À paraître',
                'img'   =>  'book-12'
            ),

            array(
                'title' =>  'À paraître',
                'img' =>  'book-13'
            ),

            array(
                'title' =>  'À paraître',
                'img'   =>  'book-14'
            ),

            array(
                'title' =>  'À paraître',
                'img' =>  'book-15'
            ),

            array(
                'title' =>  'À paraître',
                'img'   =>  'book-16'
            )
        );
        $books3 = array(
            array(
                'title' =>  'Vous pourriez aimer…',
                'img' =>  'book-17'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img'   =>  'book-18'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img' =>   'book-19'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img'   =>  'book-20'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img' =>  'book-21'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img'   =>  'book-22'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img' =>  'book-23'
            ),

            array(
                'title' =>  'Vous pourriez aimer…',
                'img'   =>  'book-24'
            )
        );
        $insta = array(
            array(
                'title' =>  'Suivez-nous sur Instagram !',
                'img' =>  'img-insta-1'
            ),

            array(
                'title' =>  'Suivez-nous sur Instagram !',
                'img'   =>  'img-insta-2'
            ),

            array(
                'title' =>  'Suivez-nous sur Instagram !',
                'img' =>   'img-insta-3'
            ),

            array(
                'title' =>  'Suivez-nous sur Instagram !',
                'img'   =>  'img-insta-4'
            ),

            array(
                'title' =>  'Suivez-nous sur Instagram !',
                'img' =>  'img-insta-5'
            ),

            array(
                'title' =>  'Suivez-nous sur Instagram !',
                'img'   =>  'img-insta-6'
            ),

        );
        $categories = [$books1,  $books2,  $books3];
        return view('pages.home')->with('categories', $categories)
            ->with('insta', $insta);
    }
}
