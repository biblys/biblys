import { beforeEach, describe, expect, it, vi } from 'vitest';
import EntitySearchField from '../../public/common/js/entity-search-field.js';

const classList = () => ({ add: vi.fn(), remove: vi.fn() });

function buildElement({ required = true, withValueInput = true, value = '', label = '' } = {}) {
  const searchInput = {
    required,
    value: label,
    readOnly: false,
    autofocus: false,
    style: {},
    validationMessage: '',
    addEventListener: vi.fn(),
    blur: vi.fn(),
    focus: vi.fn(),
    setCustomValidity(message) {
      this.validationMessage = message;
    },
  };
  const valueInput = withValueInput ? { value } : null;
  const buttonIcon = { classList: classList() };
  const button = {
    classList: classList(),
    focus: vi.fn(),
    addEventListener: vi.fn(),
    querySelector: () => buttonIcon,
  };
  const parts = {
    '.EntitySearchField__search-input': searchInput,
    '.EntitySearchField__value-input': valueInput,
    button,
    '.help-text': { classList: classList() },
    '.autocomplete-results': {
      innerHTML: '',
      children: [],
      appendChild(child) {
        this.children.push(child);
      },
      querySelectorAll: () => [],
    },
  };
  const element = {
    dataset: { query_url: '/search' },
    querySelector: (selector) => parts[selector],
    closest: () => null,
  };

  return { element, searchInput, valueInput, subMenu: parts['.autocomplete-results'] };
}

describe('EntitySearchField', () => {
  beforeEach(() => {
    globalThis.document = { addEventListener: vi.fn() };
  });

  it('is invalid while no result is selected', () => {
    const { element, searchInput } = buildElement();

    new EntitySearchField(element);

    expect(searchInput.validationMessage).toBe('Sélectionnez un résultat dans la liste.');
  });

  it('is valid when a result is already selected', () => {
    const { element, searchInput } = buildElement({ value: '12', label: 'Collection' });

    new EntitySearchField(element);

    expect(searchInput.validationMessage).toBe('');
  });

  it('becomes valid when a result is selected, and invalid again when reset', () => {
    const { element, searchInput, valueInput } = buildElement();
    const field = new EntitySearchField(element);

    field.selectResult({ value: '12', label: 'Collection' });

    expect(valueInput.value).toBe('12');
    expect(searchInput.validationMessage).toBe('');

    field.reset();

    expect(valueInput.value).toBe('');
    expect(searchInput.validationMessage).toBe('Sélectionnez un résultat dans la liste.');
  });

  it('does not touch the value input when selecting nothing', () => {
    const { element, valueInput } = buildElement();
    const field = new EntitySearchField(element);

    field.selectResult(undefined);

    expect(valueInput.value).toBe('');
  });

  it('is never invalid when the field is not required', () => {
    const { element, searchInput } = buildElement({ required: false });

    new EntitySearchField(element);

    expect(searchInput.validationMessage).toBe('');
  });

  it('does not require a value input', () => {
    const { element, searchInput } = buildElement({ withValueInput: false });

    new EntitySearchField(element);

    expect(searchInput.validationMessage).toBe('');
  });

  describe('search results', () => {
    const results = [{ label: 'Premier', value: '1' }, { label: 'Second', value: '2' }];

    function stubDocument() {
      const listeners = {};
      globalThis.document = {
        addEventListener: (type, callback) => (listeners[type] = callback),
        createElement: () => ({
          style: {},
          children: [],
          appendChild(child) {
            this.children.push(child);
          },
          addEventListener: vi.fn(),
        }),
      };
      return listeners;
    }

    function stubFetch(searchResults) {
      globalThis.fetch = vi.fn().mockResolvedValue({
        json: async () => ({ results: searchResults }),
      });
    }

    async function pressEnter(listeners, searchInput) {
      searchInput.value = 'query';
      await listeners.keydown({ key: 'Enter', preventDefault: vi.fn() });
    }

    it('displays the label of each result by default', async () => {
      const listeners = stubDocument();
      stubFetch(results);
      const { element, searchInput, subMenu } = buildElement();
      new EntitySearchField(element);

      await pressEnter(listeners, searchInput);

      expect(subMenu.children.map((item) => item.textContent)).toEqual(['Premier', 'Second']);
    });

    it('displays the element returned by renderItem when provided', async () => {
      const listeners = stubDocument();
      stubFetch(results);
      const { element, searchInput, subMenu } = buildElement();
      const renderItem = (result) => ({ rendered: result.label });
      new EntitySearchField(element, { renderItem });

      await pressEnter(listeners, searchInput);

      expect(subMenu.children.map((item) => item.children[0])).toEqual([
        { rendered: 'Premier' },
        { rendered: 'Second' },
      ]);
    });

    it('selects the only result of a submitted search when autoSelectSingleResult is set', async () => {
      const listeners = stubDocument();
      stubFetch([results[0]]);
      const { element, searchInput } = buildElement();
      const onResultSelected = vi.fn();
      new EntitySearchField(element, { autoSelectSingleResult: true, onResultSelected });

      await pressEnter(listeners, searchInput);

      expect(onResultSelected).toHaveBeenCalledWith(expect.anything(), results[0]);
    });

    it('does not select automatically when there are several results', async () => {
      const listeners = stubDocument();
      stubFetch(results);
      const { element, searchInput } = buildElement();
      const onResultSelected = vi.fn();
      new EntitySearchField(element, { autoSelectSingleResult: true, onResultSelected });

      await pressEnter(listeners, searchInput);

      expect(onResultSelected).not.toHaveBeenCalled();
    });

    it('does not select automatically unless autoSelectSingleResult is set', async () => {
      const listeners = stubDocument();
      stubFetch([results[0]]);
      const { element, searchInput } = buildElement();
      const onResultSelected = vi.fn();
      new EntitySearchField(element, { onResultSelected });

      await pressEnter(listeners, searchInput);

      expect(onResultSelected).not.toHaveBeenCalled();
    });
  });
});
