/**
 * Copyright (C) 2025 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


/* Stripe */

const stripePayButton = document.getElementById('pay-with-stripe');
if (stripePayButton) {
  stripePayButton.addEventListener('click', async function() {
    const payButtonLoader = document.getElementById('pay-with-stripe-loader');
    stripePayButton.disabled = true;
    payButtonLoader.classList.remove('d-none');

    const createStripePaymentUrl = stripePayButton.getAttribute('data-payment-url');
    const response = await fetch(createStripePaymentUrl, {
      method: 'POST',
      headers: { accept: 'application/json' }
    });
    const responseData = await response.json();

    if (responseData.error) {
      stripePayButton.disabled = false;
      payButtonLoader.classList.add('d-none');
      window._alert(responseData.error.message);
      return;
    }

    const stripePublicKey = stripePayButton.getAttribute('data-public-key');
    const stripe = window.Stripe(stripePublicKey);
    stripe.redirectToCheckout({
      sessionId: responseData.session_id
    }).then(function(result) {
      stripePayButton.disabled = false;
      payButtonLoader.classList.add('d-none');
      window._alert(result.error.message);
    });
  });
}

/* PayPal */

const paypalPayButton = document.getElementById('pay-with-paypal');
if (paypalPayButton) {

  const createOrderUrl = paypalPayButton.getAttribute('data-create-order-url');
  const capturePaymentUrl = paypalPayButton.getAttribute('data-capture-payment-url');
  const legacyOrderUrl = paypalPayButton.getAttribute('data-order-url');

  async function onPaypalButtonClick() {
    try {
      const response = await fetch(createOrderUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
      });
      const orderData = await response.json();

      if (!orderData.id) {
        const errorDetail = orderData?.details?.[0];
        const errorMessage = errorDetail
          ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
          : JSON.stringify(orderData);

        throw new Error(errorMessage);
      }

      return orderData.id;
    } catch (error) {
      window._alert(`Impossible de démarrer la transaction PayPal : ${error}`);
    }
  }

  async function onPayPalApproveCallback(data, actions) {
    try {
      const response = await fetch(capturePaymentUrl, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ paypalOrderId: data.orderID })
      });

      const orderData = await response.json();
      // Three cases to handle:
      //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
      //   (2) Other non-recoverable errors -> Show a failure message
      //   (3) Successful transaction -> Show confirmation or thank you message

      const transaction =
        orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
        orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
      const errorDetail = orderData?.details?.[0];

      // this actions.restart() behavior only applies to the Buttons component
      if (errorDetail?.issue === 'INSTRUMENT_DECLINED' && !data.card && actions) {
        // (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
        // recoverable state, per https://developer.paypal.com/docs/checkout/standard/customize/handle-funding-failures/
        return actions.restart();
      } else if (errorDetail || !transaction || transaction.status === 'DECLINED') {
        // (2) Other non-recoverable errors -> Show a failure message
        let errorMessage;
        if (transaction) {
          errorMessage = `Transaction ${transaction.status}: ${transaction.id}`;
        } else if (errorDetail) {
          errorMessage = `${errorDetail.description} (${orderData.debug_id})`;
        } else {
          errorMessage = JSON.stringify(orderData);
        }

        throw new Error(errorMessage);
      }

      window.location.href = legacyOrderUrl;
    } catch (error) {
      window._alert(`La transaction PayPal n'a pas aboutie à cause d'une erreur : ${error}`);
    }
  }

  window.paypal.Buttons({
    createOrder: onPaypalButtonClick,
    onApprove: onPayPalApproveCallback
  }).render('#pay-with-paypal');
}