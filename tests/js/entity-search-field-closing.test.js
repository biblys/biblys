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

const response = { json: async () => ({ results: [{ label: 'Premier', value: '1' }] }) };

let fetchMock;

beforeEach(() => {
  fetchMock = vi.fn().mockResolvedValue(response);
  vi.stubGlobal('fetch', fetchMock);
  document.body.innerHTML = `
    <div id="field" class="EntitySearchField" data-query_url="/search">
      <input class="EntitySearchField__search-input" value="query">
      <button type="button"><i></i></button>
      <div class="autocomplete-results"></div>
    </div>
    <button id="outside" type="button"></button>`;
  new EntitySearchField(document.getElementById('field'));
});

afterEach(() => {
  vi.unstubAllGlobals();
});

const input = () => document.querySelector('.EntitySearchField__search-input');
const results = () => document.querySelectorAll('.autocomplete-results > a');

function pressKey(key) {
  const event = new KeyboardEvent('keydown', { key, bubbles: true, cancelable: true });
  input().dispatchEvent(event);
  return event;
}

async function displayResults() {
  input().focus();
  pressKey('Enter');
  await vi.waitFor(() => expect(results()).toHaveLength(1));
}

async function displayResultsAfterAnUnansweredSearch() {
  input().focus();
  pressKey('Enter');
  pressKey('Enter');
  await vi.waitFor(() => expect(results()).toHaveLength(1));
}

const flushResponses = () => new Promise((resolve) => setTimeout(resolve));

describe('EntitySearchField closing', () => {
  it('closes the results on Escape and keeps the query', async () => {
    // given
    await displayResults();

    // when
    const event = pressKey('Escape');

    // then
    expect(results()).toHaveLength(0);
    expect(input().value).toBe('query');
    expect(event.defaultPrevented).toBe(true);
  });

  it('leaves Escape alone when there is nothing to close', () => {
    // when
    const event = pressKey('Escape');

    // then
    expect(event.defaultPrevented).toBe(false);
  });

  it('closes the results on a click outside of the field', async () => {
    // given
    await displayResults();

    // when
    document.getElementById('outside').click();

    // then
    expect(results()).toHaveLength(0);
  });

  it('keeps the results on a click inside of the field', async () => {
    // given
    await displayResults();

    // when
    input().click();

    // then
    expect(results()).toHaveLength(1);
  });

  it('closes the results when the focus leaves the field', async () => {
    // given
    await displayResults();

    // when
    document.getElementById('outside').focus();

    // then
    expect(results()).toHaveLength(0);
  });

  it('keeps the results when the input only loses the focus', async () => {
    // given
    await displayResults();

    // when
    input().blur();

    // then
    expect(results()).toHaveLength(1);
  });

  it('searches again when Enter is pressed after closing a highlighted result', async () => {
    // given
    await displayResults();
    pressKey('ArrowDown');
    pressKey('Escape');

    // when
    pressKey('Enter');

    // then
    await vi.waitFor(() => expect(results()).toHaveLength(1));
    expect(fetchMock).toHaveBeenCalledTimes(2);
  });

  it('ignores a response received after closing', async () => {
    // given
    let respond;
    fetchMock.mockReturnValue(new Promise((resolve) => (respond = resolve)));
    input().focus();
    pressKey('Enter');
    document.getElementById('outside').click();

    // when
    respond(response);
    await flushResponses();

    // then
    expect(results()).toHaveLength(0);
  });

  it('ignores the response of a search outdated by a newer one', async () => {
    // given
    let respondToOutdatedSearch;
    fetchMock.mockReturnValueOnce(new Promise((resolve) => (respondToOutdatedSearch = resolve)));
    await displayResultsAfterAnUnansweredSearch();

    // when
    respondToOutdatedSearch({ json: async () => ({ results: [] }) });
    await flushResponses();

    // then
    expect(results()).toHaveLength(1);
  });

  it('cancels a delayed search when closing', () => {
    // given
    vi.useFakeTimers();
    input().dispatchEvent(new Event('input'));
    pressKey('Escape');

    // when
    vi.runAllTimers();

    // then
    expect(fetchMock).not.toHaveBeenCalled();
    vi.useRealTimers();
  });
});
