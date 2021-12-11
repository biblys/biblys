<?php

namespace Biblys\Legacy;

use Biblys\Exception\OrderDetailsValidationException;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;

class OrderDeliveryHelpers
{

    /**
     * @throws OrderDetailsValidationException
     * @throws Exception
     */
    public static function validateOrderDetails($request)
    {
        if (empty($request->request->get('order_firstname'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Pr&eacute;nom&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('order_lastname'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Nom&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('order_address1'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Adresse&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('order_postalcode'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Code Postal&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('order_city'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Ville&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('order_email'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Adresse e-mail&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('country_id'))) {
            throw new OrderDetailsValidationException(
                'Le champ &laquo;&nbsp;Pays&nbsp;&raquo; est obligatoire !'
            );
        }

        if (empty($request->request->get('cgv_checkbox'))) {
            throw new OrderDetailsValidationException(
                'Vous devez accepter les Conditions Générales de Vente.'
            );
        }

        // Validate e-mail
        $orderEmail = $request->request->get('order_email');
        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);
        $isEmailValid = $validator->isValid($orderEmail, $multipleValidations);
        if (!$isEmailValid) {
            throw new Exception("L'adresse e-mail est pas valide.");
        }
    }
}