@extends('layouts.app')

@section('content')
    <div class="h-full">
        <div class="px-view py-view mx-auto">
            <div class="mx-auto py-8 max-w-sm text-center text-90">
                @include('admin.audiobooks.logo', ['width' => 200, 'height' => 39])
            </div>
            <div class="bg-white shadow rounded-lg p-8 mx-auto">
                @csrf
                <h2 class="text-2xl text-center font-normal mb-6 text-90">Générateur de chapitres pour le livre : {{ $audiobook->name }} </h2>
                <svg class="block mx-auto mb-6" xmlns="http://www.w3.org/2000/svg" width="100" height="2" viewBox="0 0 100 2">
                  <path fill="#D8E3EC" d="M0 0h100v2H0z"></path>
                </svg>
                <p>
                    Nombre de chapitres : {{ $chapter_count }}
                </p>

                <form action="{{ route('chapters.upload') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label for="form-file">Télécharger le fichier</label>
                    <input type="file" name="file" id="form-file" class="hidden" />
                    <input type="hidden" value="{{ $audiobook->id }}" name="audiobook_id" id="audiobook_id"/><br>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
