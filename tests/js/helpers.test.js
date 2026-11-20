// @vitest-environment happy-dom
import { describe, expect, it } from 'vitest';
import eurosToCents from '../../assets/js/helpers/eurosToCents';
import formatAmount from '../../assets/js/helpers/formatAmount';
import safeHtml, { escapeHtml } from '../../assets/js/helpers/safeHtml';

describe('formatAmount', () => {
  it('formats an amount in cents as euros with a decimal comma', () => {
    // when
    const formattedAmount = formatAmount(1250);

    // then
    expect(formattedAmount).toBe('12,50 €');
  });

  it('accepts cents given as a string', () => {
    // when
    const formattedAmount = formatAmount('1250');

    // then
    expect(formattedAmount).toBe('12,50 €');
  });

  it('formats zero', () => {
    // when
    const formattedAmount = formatAmount(0);

    // then
    expect(formattedAmount).toBe('0,00 €');
  });

  it('rejects values that are not numbers', () => {
    // when
    const formatNotANumber = () => formatAmount('abc');
    const formatUndefined = () => formatAmount(undefined);

    // then
    expect(formatNotANumber).toThrow('Amount must be a number of cents');
    expect(formatUndefined).toThrow('Amount must be a number of cents');
  });
});

describe('eurosToCents', () => {
  it('converts euros to whole cents without floating point drift', () => {
    // when
    const fromString = eurosToCents('12.1');
    const fromNumber = eurosToCents(0.29);

    // then
    expect(fromString).toBe(1210);
    expect(fromNumber).toBe(29);
  });

  it('treats empty or invalid values as zero', () => {
    // when
    const fromEmpty = eurosToCents('');
    const fromInvalid = eurosToCents('abc');

    // then
    expect(fromEmpty).toBe(0);
    expect(fromInvalid).toBe(0);
  });
});

describe('escapeHtml', () => {
  it('escapes the characters that are special in HTML', () => {
    // given
    const value = `<a href="x">Tom & 'Jerry'</a>`;

    // when
    const escapedValue = escapeHtml(value);

    // then
    expect(escapedValue).toBe('&lt;a href=&quot;x&quot;&gt;Tom &amp; &#39;Jerry&#39;&lt;/a&gt;');
  });
});

describe('safeHtml', () => {
  it('builds an element and escapes interpolated values', () => {
    // given
    const title = '<img src=x onerror=alert(1)>';

    // when
    const element = safeHtml`<span class="title">${title}</span>`;

    // then
    expect(element.tagName).toBe('SPAN');
    expect(element.querySelector('img')).toBeNull();
    expect(element.textContent).toBe(title);
  });
});
