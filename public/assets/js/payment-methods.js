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

document.addEventListener('DOMContentLoaded', function() {
  const firstPaymentOption = document.querySelector('.payment-option a');
  if (firstPaymentOption) {
    firstPaymentOption.click();
  }
});


/* Stripe */

async function loadStripeForm(paymentForm) {
  const publicKey = paymentForm.getAttribute('data-public-key');
  const paymentCreationUrl = paymentForm.getAttribute('data-payment-url');
  const paymentButton = document.getElementById('stripe-payment-button');
  const paymentButtonLoader = document.getElementById('stripe-payment-button-loader');

  const response = await fetch(paymentCreationUrl, {
    method: 'POST',
    headers: { accept: 'application/json' }
  });
  /**
   * @var responseData {object}
   * @var responseData.payment_intent_client_secret {string} The client secret for the payment intent
   * @var responseData.customer_session_client_secret {string} The client secret for the customer session
   */
  const responseData = await response.json();
  if (responseData.error) {
    window._alert(responseData.error.message);
  } else {
    const stripe = window.Stripe(publicKey);
    const paymentIntentClientSecret = responseData.payment_intent_client_secret;
    const customerSessionClientSecret = responseData.customer_session_client_secret;
    const elements = stripe.elements({ clientSecret: paymentIntentClientSecret, customerSessionClientSecret });
    const paymentElement = elements.create('payment', {
      disableLink: true,
    });
    paymentElement.mount('#payment-element');
    paymentElement.on('ready', function() {
      paymentButton.classList.remove('d-none');
    });

    paymentForm.addEventListener('submit', async function(event) {
      event.preventDefault();
      paymentButton.disabled = true;
      paymentButtonLoader.classList.remove('d-none');

      const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
          return_url: paymentForm.getAttribute('data-order-url')
        }
      });

      paymentButton.disabled = false;
      paymentButtonLoader.classList.add('d-none');

      if (error.type === 'validation_error') {
        return;
      }

      const errorMessage = `Le paiement a échoué. ${error.message} Pour plus d'informations, veuillez contacter la banque émettrice de la carte.`;
      window._alert(errorMessage);
    });
  }
}
const stripePaymentForm = document.getElementById('stripe-payment-form');
if (stripePaymentForm) {
  loadStripeForm(stripePaymentForm).then();
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