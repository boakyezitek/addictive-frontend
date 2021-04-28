<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed'   => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'throttle' => 'Tentatives de connexion trop nombreuses. Veuillez essayer de nouveau dans :seconds secondes.',
    'unauthenticated' => 'Vous êtes non authentifié',

    'email'    => 'Adresse Email',
    'password' => 'Mot de passe',
    'confirm'  => 'Confirmation mot de passe',

    'check_success' => 'Les champs transmis sont valides.',

    'errors' => [
        'email' => 'Veuillez entrer une adresse email valide.',
        'email_used' => 'Cette adresse email existe déjà, avez vous essayé de vous connecter ?',
        'email_not_used' => 'Cet email n\'est lié à aucun compte, avez vous essayé de vous inscrire ?',
        'email_used_update' => 'Cette adresse email existe déjà.',
        'old_password' => 'votre ancien mot de passe est incorrecte.',
        'password' => 'Votre mot de passe doit contenir au minimum 8 caractères avec au moins une lettre et un chiffre.',
        'password_confirmed' => 'Le mot de passe et sa confirmation doivent être identiques.',
        'cgu' => 'Vous devez accepter les conditions d\'utilisation pour continuer.',
        'unverified_email' => 'Veuillez vérifier vos informations de connexion. Si vous vous êtes bien inscrits, vérifiez que vous avez validé votre compte.',
    ],
    'Registration attempt' => 'Tentative d\'inscription',
    'You are receiving this email because someone tried to register with this email.' => 'Vous avez essayé de vous inscrire à l’application Addictives, mais vous avez déjà un compte chez nous et nous vous en remercions ! ',
    'Since you already have an account here is a link to the login view.' => 'Dans tous les cas, vous pouvez vous connecter à l’application via le bouton ci-dessous',
    'Connection' => 'Connexion',
    'forgotten' => 'Ou, si vous avez oublié votre mot de passe, vous pouvez le réinitialiser via ce lien : :link',
    'reset_password' => 'Réinitialiser mon mot de passe ',
    'If the attempt does not comes from you, no further action is required.' => 'Si vous n’êtes pas à l’origine de cette tentative d’inscription, aucune action n’est requise.',
    'Your account is linked to :provider' => "Votre compte a été créé via :provider, il est possible que vous deviez l'utiliser pour vous connecter, dans l'application cliquez sur J'ai déjà un compte puis Continuer avec :provider",
    'If you do not have access to your social account, you can still ask for a password reset to set a password to your account.' => 'Si vous n\'avez plus accès à votre compte réseau social, vous pouvez toujours faire une demande de réinitialisation de mot de passe.',

    'social_media' => [
        'wrong_provider' => 'Votre compte n\'a pas été créé via :provider, vous ne pouvez donc pas y accéder en vous connectant avec :provider',
    ],
];
