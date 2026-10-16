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
    '.autocomplete-results': { innerHTML: '', querySelectorAll: () => [] },
  };
  const element = {
    dataset: { query_url: '/search' },
    querySelector: (selector) => parts[selector],
    closest: () => null,
  };

  return { element, searchInput, valueInput };
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
});
