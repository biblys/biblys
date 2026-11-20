import { describe, expect, it } from 'vitest';
import { calculateCartTotals } from '../../assets/js/point-of-sale/payments';

describe('calculateCartTotals', () => {
  it('sums item prices and counts items', () => {
    // given
    const itemPrices = [1000, 550];
    const payments = [];

    // when
    const totals = calculateCartTotals({ itemPrices, payments });

    // then
    expect(totals).toEqual({ count: 2, total: 1550, toPay: 1550, toGive: 0 });
  });

  it('computes the amount left to pay when payments are partial', () => {
    // given
    const itemPrices = [1550];
    const payments = [500, 0, 200];

    // when
    const totals = calculateCartTotals({ itemPrices, payments });

    // then
    expect(totals.toPay).toBe(850);
    expect(totals.toGive).toBe(0);
  });

  it('computes the change to give back when payments exceed the total', () => {
    // given
    const itemPrices = [1550];
    const payments = [2000, 0, 0];

    // when
    const totals = calculateCartTotals({ itemPrices, payments });

    // then
    expect(totals.toPay).toBe(0);
    expect(totals.toGive).toBe(450);
  });

  it('handles an empty cart', () => {
    // given
    const itemPrices = [];
    const payments = [0, 0, 0];

    // when
    const totals = calculateCartTotals({ itemPrices, payments });

    // then
    expect(totals).toEqual({ count: 0, total: 0, toPay: 0, toGive: 0 });
  });
});
