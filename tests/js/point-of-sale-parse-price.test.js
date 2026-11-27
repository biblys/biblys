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
import { describe, expect, it } from 'vitest';
import parsePrice from '../../assets/js/point-of-sale/parse-price';

describe('parsePrice', () => {
  it.each([
    ['8', 800],
    ['8,5', 850],
    ['8.50', 850],
    [' 12,99 ', 1299],
    ['0', 0],
  ])('converts %j to cents', (input, expected) => {
    // when
    const price = parsePrice(input);

    // then
    expect(price).toBe(expected);
  });

  it.each(['', '  ', 'abc', '-1', '8,505', '1,2,3', '8 €'])('rejects %j', (input) => {
    // when
    const price = parsePrice(input);

    // then
    expect(price).toBeNull();
  });
});
