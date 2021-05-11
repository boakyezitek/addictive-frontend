<div class="manu__wrapper">
    <div class="back__area">
        <img src="img/icons/ic-arrow-back.png" alt="">
        <span>RETOUR</span>
    </div>
    <div class="title__area">
        <span class="manu__title">Le manuscrit</span>
        <div class="progress__btn">2/2</div>
    </div>
    <span class="manu__subtitle">Diffusion</span>
    <p class="contract__context manu__context">Votre récit a-t-il déjà fait l'objet d'un contrat ?</p>
    <div>
        <div class="checkbox__btn">Oui</div>
        <div class="checkbox__btn">Non</div>
    </div>
    <p class="release__context manu__context">Votre récit a-t-il déjà été diffusé à titre gratuit ? </p>
    <div>
        <div class="checkbox__btn">Oui</div>
        <div class="checkbox__btn">Non</div>
    </div>
    <span class="manuscript__context">Le manuscrit</span>
    <input type="text" class="manuscript__title" placeholder="Titre du manuscrit">
    <input type="text" class="gender" placeholder="Genre(s)">
    <input type="text" class="signs__number" placeholder="Nombres de signes (espaces comprises)">
    <span class="story__context">L’histoire</span>
    <p class="summarize__context manu__context">Comment résumeriez-vous votre manuscrit pour donner envie de le lire ? : </p>
    <textarea rows="4" cols="50" name="summary" class="summary" placeholder="Résumé (max. 1000 caractères)"></textarea>

    <p class="character__context manu__context">Présentation des personnages :</p>
    <textarea rows="4" cols="50" name="character" class="character" placeholder="Présentation des personnages : (max. 1000 caractères)"></textarea>

    <p class="mainplot__context manu__context">Quelle est l'intrigue principale ? Et comment est-elle résolue ? : </p>
    <textarea rows="4" cols="50" name="plot" class="plot" placeholder="L’intrigue… (max. 1000 caractères)"></textarea>

    <p class="important__info__context">Avez-vous d'autres informations importantes à nous transmettre ?</p>
    <textarea rows="4" cols="50" name="etc__info" class="etc__info" placeholder="Autres informations… (max. 1000 caractères)"></textarea>


    <p class="file__context manu__context">Le fichier</p>
    <p class="add__attachment__context manu__context">Ajoutez votre manuscrit en pièce jointe (assurez-vous de bien respecter les conditions de présentation : tout manuscrit ne les respectant pas ne pourra être reçu) :</p>

    <div class="spot__area">
        <span>Joindre le fichier</span>
    </div>

    <div class="msg__context">
        <div>
            <img src="img/writeform/ic-radio-off.png" srcset="img/writeform/ic-radio-off@2x.png 2x,
             img/writeform/ic-radio-off@3x.png 3x" class="icradio_off">
        </div>
        <p class="manu__context">
            En cochant cette case, et sauf informations contraires de votre part, vous garantissez automatiquement que vous détenez les droits tous formats du manuscrit présenté. Vous confirmez également être âgé(e) d'au moins 18 ans et vous acceptez que les informations saisies soient utilisées dans le cadre de l'envoi de manuscrit organisé par les Éditions Addictives. Si, avant que nous vous répondions, vous acceptez une offre de contrat d'un autre éditeur, soyez aimable de nous le préciser au plus vite afin que nous sortions votre manuscrit de notre listing.
        </p>
    </div>

    
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div><img src="img/icons/img-mail.png" class="modal__img" alt=""></div>
                <span class="modal__header__title">Manuscrit envoyé !</span>
                <div class="sub__container">
                    <span class="modal__header__subtitle">
                        Vous recevrez sous 24h un e-mail<br>
                        confirmant la réception de votre manuscrit. <br>
                        Si ce n’est pas le cas, pensez à vérifier dans <br>
                        vos spams.</span>
                </div>
                <button class="modal__btn">Retour à l’accueil</button>
            </div>

        </div>
    </div>
    <div class="btn__area">
        <button id="setting__cancel__btn  v-pills-settings-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" class="submit-button1">Annuler</button>
        <button id="setting__nextstep__btn" class="submit-button2" data-toggle="modal" data-target="#myModal">Étape suivante</button>
    </div>
</div>