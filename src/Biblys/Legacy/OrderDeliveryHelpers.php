<?php

namespace Biblys\Legacy;

use Biblys\Exception\OrderDetailsValidationException;
use Cart;
use CountryManager;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use OrderManager;
use Shipping;
use ShippingManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Visitor;

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

    /**
     * @param Visitor $visitor
     * @return false|mixed|null
     */
    public static function getOrderInProgressForVisitor(Visitor $visitor)
    {
        if (!$visitor->isLogged()) {
            return null;
        }

        $om = new OrderManager();
        return $om->get([
            'order_type' => 'web',
            'user_id' => $visitor->get('id'),
            'order_payment_date' => 'NULL',
            'order_shipping_date' => 'NULL',
            'order_cancel_date' => 'NULL'
        ]);
    }

    /**
     * @param Cart $cart
     * @param int|null $countryId
     * @param string|null $visitorCountry
     * @return string
     * @throws Exception
     */
    public static function getCountryInput(Cart $cart, ?int $countryId, ?string $visitorCountry): string
    {
        $com = new CountryManager();
        if ($cart->needsShipping()) {
            if (!is_int($countryId)) {
                throw new BadRequestHttpException("country_id parameter is required when cart needs shipping");
            }
            $country = $com->getById($countryId);
            if (!$country) {
                trigger_error('Pays incorrect');
            }
            $countryInput = '
            <input type="text" class="order-delivery-form__input" value="' . $country->get('name') . '" readonly>
            <input type="hidden" name="country_id" value="' . $country->get('id') . '">
            <a class="btn btn-light" href="/pages/cart">modifier</a>
        ';
        } else {

            $countries = $com->getAll();
            $countriesOptions = array_map(function ($country) use ($visitorCountry) {
                $selected = null;
                if ($country->get('name') === $visitorCountry) {
                    $selected = " selected";
                }
                return '<option value="' . $country->get('id') . '" ' . $selected . '>' . $country->get('name') . '</option>';
            }, $countries);

            $countryInput = '
            <select name="country_id">
                <option></option>
                <option value="67">France</option>
                ' . implode($countriesOptions) . '
            </select>
        ';
        }
        return $countryInput;
    }

    /**
     * @param Cart $cart
     * @param int|null $shippingIdFromRequest
     * @return Shipping|null
     */
    public static function calculateShippingFees(Cart $cart, ?int $shippingIdFromRequest): ?Shipping
    {
        if ($cart->needsShipping()) {
            if (!is_int($shippingIdFromRequest)) {
                throw new BadRequestHttpException("shipping_id parameter is required when cart needs shipping");
            }
            $shm = new ShippingManager();
            $shipping = $shm->getById($shippingIdFromRequest);
            if (!$shipping) {
                trigger_error("Frais de port incorrect.");
            }

            return $shipping;
        }

        return null;
    }
}
