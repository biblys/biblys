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

let visualViewport;
let resultsTop;

beforeEach(() => {
  vi.stubGlobal(
    'fetch',
    vi.fn().mockResolvedValue({ json: async () => ({ results: [{ label: 'Premier' }] }) }),
  );
  visualViewport = Object.assign(new EventTarget(), { offsetTop: 0, height: 720 });
  vi.stubGlobal('innerHeight', 720);
  vi.stubGlobal('visualViewport', visualViewport);
  document.body.innerHTML = `
    <div id="field" class="EntitySearchField" data-query_url="/search">
      <input class="EntitySearchField__search-input" value="query">
      <button type="button"><i></i></button>
      <div class="autocomplete-results"></div>
    </div>`;
  resultsList().getBoundingClientRect = () => ({ top: resultsTop });
  new EntitySearchField(document.getElementById('field'));
});

afterEach(() => {
  vi.unstubAllGlobals();
});

const resultsList = () => document.querySelector('.autocomplete-results');

async function displayResultsAt(top) {
  resultsTop = top;
  document
    .querySelector('.EntitySearchField__search-input')
    .dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
  await vi.waitFor(() => expect(resultsList().children).toHaveLength(1));
}

describe('EntitySearchField results height', () => {
  it.each([
    ['60% of the screen when there is plenty of room', 70, '432px'],
    ['the space left below the field', 500, '204px'],
    ['a minimum height when the field is at the bottom', 650, '200px'],
  ])('is limited to %s', async (_, top, expectedHeight) => {
    // when
    await displayResultsAt(top);

    // then
    expect(resultsList().style.maxHeight).toBe(expectedHeight);
  });

  it('fits above the area hidden by a mobile keyboard', async () => {
    // given
    visualViewport.height = 500;

    // when
    await displayResultsAt(150);

    // then
    expect(resultsList().style.maxHeight).toBe('334px');
  });

  it('is updated when the page is scrolled', async () => {
    // given
    await displayResultsAt(500);
    resultsTop = 300;

    // when
    document.dispatchEvent(new Event('scroll'));

    // then
    expect(resultsList().style.maxHeight).toBe('404px');
  });

  it('is updated when the visible area is resized', async () => {
    // given
    await displayResultsAt(150);
    visualViewport.height = 500;

    // when
    visualViewport.dispatchEvent(new Event('resize'));

    // then
    expect(resultsList().style.maxHeight).toBe('334px');
  });
});
