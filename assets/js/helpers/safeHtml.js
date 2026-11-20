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

import createElementFromHTML from './createElementFromHTML';

const HTML_ESCAPES = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };

export function escapeHtml(value) {
  return String(value).replace(/[&<>"']/g, (char) => HTML_ESCAPES[char]);
}

/**
 * Tagged template that builds an element from HTML, escaping every
 * interpolated value to prevent HTML injection.
 *
 * Escaping protects text and quoted attribute values only, not unquoted
 * attributes nor URLs (href, src) that could start with "javascript:".
 *
 * @example safeHtml`<span class="title">${article.title}</span>`
 */
export default function safeHtml(strings, ...values) {
  const markup = strings.reduce(
    (result, string, index) =>
      result + string + (index < values.length ? escapeHtml(values[index]) : ''),
    '',
  );
  return createElementFromHTML(markup);
}
