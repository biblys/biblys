import { readFileSync } from 'node:fs';
import { describe, expect, it } from 'vitest';

// common.js is a global script (not a module): extract the helpers and
// evaluate them with a stubbed createElementFromHTML that returns the markup.
const source = readFileSync(new URL('../../public/common/js/common.js', import.meta.url), 'utf8');
const start = source.indexOf('const HTML_ESCAPES');
const end = source.indexOf('/* NOTIFICATIONS */');
if (start === -1 || end === -1) {
  throw new Error('Could not find the safeHtml helpers in common.js');
}
const { escapeHtml, safeHtml } = new Function(
  'createElementFromHTML',
  `${source.slice(start, end)}; return { escapeHtml, safeHtml };`,
)((markup) => markup);

describe('escapeHtml', () => {
  it('escapes the characters that are special in HTML', () => {
    expect(escapeHtml(`<a href="x">Tom & 'Jerry'</a>`)).toBe(
      '&lt;a href=&quot;x&quot;&gt;Tom &amp; &#39;Jerry&#39;&lt;/a&gt;',
    );
  });

  it('converts non-string values to strings', () => {
    expect(escapeHtml(42)).toBe('42');
  });
});

describe('safeHtml', () => {
  it('escapes interpolated values and leaves the template untouched', () => {
    const title = '<img src=x onerror=alert(1)>';

    const markup = safeHtml`<span class="title">${title}</span>`;

    expect(markup).toBe('<span class="title">&lt;img src=x onerror=alert(1)&gt;</span>');
  });

  it('escapes every interpolated value', () => {
    const markup = safeHtml`<p>${'<b>'} and ${'&'}</p>`;

    expect(markup).toBe('<p>&lt;b&gt; and &amp;</p>');
  });

  it('supports a template without interpolation', () => {
    expect(safeHtml`<hr>`).toBe('<hr>');
  });

  it('supports a value at the end of the template', () => {
    expect(safeHtml`<p>${'<'}`).toBe('<p>&lt;');
  });
});
