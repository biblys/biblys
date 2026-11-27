/**
 * Copyright (C) 2026 Clément Latzarus
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

import { readFileSync } from 'node:fs';
import { describe, expect, it, vi } from 'vitest';

const script = readFileSync(
  new URL('../../public/common/js/biblys-admin.js', import.meta.url),
  'utf8',
);

// biblys-admin.js is a global script: it can only be run, not imported
function loadCheckoutForm({ values, dataValues }) {
  let submitCheckoutForm;
  const jQueryStub = (selector) => {
    const element = new Proxy({}, {
      get(target, method) {
        if (method === 'val') return () => values[selector] ?? '';
        if (method === 'attr') return () => dataValues[selector];
        if (method === 'submit') {
          return (handler) => {
            if (selector === '#checkout.event') submitCheckoutForm = handler;
            return element;
          };
        }
        if (method === 'length') return 0;
        return () => element;
      },
    });
    return element;
  };
  const fetchSpy = vi.fn().mockResolvedValue({ ok: true });
  const windowStub = { biblys: {}, location: {} };
  const documentStub = {
    addEventListener: () => {},
    querySelector: () => null,
    querySelectorAll: () => [],
  };
  const biblysStub = new Proxy(function() {}, { get: () => ({}) });

  new Function('$', 'window', 'document', 'fetch', 'Biblys', `${script}\nreloadAdminEvents();`)(
    jQueryStub, windowStub, documentStub, fetchSpy, biblysStub,
  );

  return {
    submit: () => submitCheckoutForm({ preventDefault: () => {} }),
    sentBody: () => new URLSearchParams(fetchSpy.mock.calls[0][1].body),
  };
}

describe('point of sale checkout', () => {
  it.each(['cart_cash', 'cart_cheque', 'cart_card'])(
    'sends %s as a whole number of cents',
    (field) => {
      // given
      const checkout = loadCheckoutForm({
        values: { [`#${field}`]: '19.99' },
        dataValues: { '#cart_total': 1999, '#cart_topay': 0, '#cart_togive': 0 },
      });

      // when
      checkout.submit();

      // then
      expect(checkout.sentBody().get(field)).toBe('1999');
    },
  );
});
