@extends('layouts.app')

@section('content')
    <div class="h-full">
        <div class="px-view py-view mx-auto">
            <div class="mx-auto py-8 max-w-sm text-center text-90">
                @include('vendor.nova.partials.logo', ['width' => 200, 'height' => 39])
            </div>
            <form class="bg-white shadow rounded-lg p-8 max-w-login mx-auto" method="POST" action="{{ route('password.api.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <h2 class="text-2xl text-center font-normal mb-6 text-90">Réinitialiser le mot de passe</h2>
                <svg class="block mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" width="100" height="2" viewBox="0 0 100 2">
                  <path fill="#D8E3EC" d="M0 0h100v2H0z"></path>
                </svg>
                <div class="mb-6 ">
                    <label class="block font-bold mb-2" for="email">Adresse email</label>
                    <input class="form-control form-input form-input-bordered w-full @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-6 ">
                    <label class="block font-bold mb-2" for="password">Mot de passe</label>
                    <input class="form-control form-input form-input-bordered w-full @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-6 ">
                    <label class="block font-bold mb-2" for="password-confirm">Confirmer le mot de passe</label>
                    <input class="form-control form-input form-input-bordered w-full" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

                <button class="w-full btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                    Réinitialiser le mot de passe
                </button>
            </form>
        </div>
    </div>
@endsection
