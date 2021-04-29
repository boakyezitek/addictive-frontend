@extends('layouts.app')

@section("content")
<section class="add__contact__section">
    <div class="contact-wrapper">
        <p class="contact__title">Nous contacter</p>
        <p class="contact__subtitle">Vous pouvez nous contacter en cliquant ici ou en utilisant le formulaire de contact ci-dessous :</p>
        <form class="contact__form">
            <div class="first__wrapper">
                    <input type="text" class="email" placeholder="Votre email" />
                    <input type="text" class="subjet" placeholder="Sujet" />
            </div>
            <div class="second__wrapper">
                <textarea type="text" class="message" placeholder="Votre message…"></textarea>
            </div>
            <input type = "submit" class="submit_btn"/>
        </form>
    </div>
</section>
@endsection