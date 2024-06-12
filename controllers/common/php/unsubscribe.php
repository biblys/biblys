<?php

    $email = $request->query->get('email', null);
    $url = $urlgenerator->generate('mailing_unsubscribe', ["email" => $email]);
    redirect($url);
