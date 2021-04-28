@extends('layouts.app')

@section('content')
    <div class="h-full">
        <div class="px-view py-view mx-auto">
            <div class="mx-auto py-8 max-w-sm text-center text-90">
                @include('vendor.nova.partials.logo', ['width' => 200, 'height' => 39])
            </div>
            <div class="bg-white shadow rounded-lg p-8 max-w-login mx-auto">
                @csrf
                <h2 class="text-2xl text-center font-normal mb-6 text-90">Mot de passe réinitialisé</h2>
                <svg class="block mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" width="100" height="2" viewBox="0 0 100 2">
                  <path fill="#D8E3EC" d="M0 0h100v2H0z"></path>
                </svg>
                <p>
                    Félicitations, votre mot de passe a été correctement réinitialisé !
                </p>
                    Voici un lien vous redirigeant sur l'application 
                </p>
                <a href="{{ route('app.login') }}" class="w-full btn btn-default btn-primary hover:bg-primary-dark text-center" type="submit">
                    Ouvrir l'application
                </a>
            </div>
        </div>
    </div>
@endsection
