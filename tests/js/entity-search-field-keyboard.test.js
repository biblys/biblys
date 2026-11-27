// @vitest-environment happy-dom
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
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import EntitySearchField from '../../public/common/js/entity-search-field.js';

let fetchMock;

beforeEach(() => {
  fetchMock = vi.fn().mockResolvedValue({ json: async () => ({ results: [] }) });
  vi.stubGlobal('fetch', fetchMock);
  document.body.innerHTML = `
    <div id="field" class="EntitySearchField" data-query_url="/search">
      <input class="EntitySearchField__search-input" value="query">
      <button type="button"><i></i></button>
      <span class="help-text d-none"></span>
      <div class="autocomplete-results"></div>
    </div>
    <input id="outside">`;
  new EntitySearchField(document.getElementById('field'));
});

afterEach(() => {
  vi.unstubAllGlobals();
});

function pressEnter(selector) {
  const event = new KeyboardEvent('keydown', { key: 'Enter', bubbles: true, cancelable: true });
  document.querySelector(selector).dispatchEvent(event);
  return event;
}

describe('EntitySearchField keyboard', () => {
  it('leaves the Enter key alone outside of the field', () => {
    // when
    const event = pressEnter('#outside');

    // then
    expect(event.defaultPrevented).toBe(false);
    expect(fetchMock).not.toHaveBeenCalled();
  });

  it('searches when Enter is pressed in the search input', () => {
    // when
    const event = pressEnter('.EntitySearchField__search-input');

    // then
    expect(event.defaultPrevented).toBe(true);
    expect(fetchMock).toHaveBeenCalledWith('/search?term=query', { method: 'GET' });
  });
});
