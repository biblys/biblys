export default function loadModuleForElements(moduleName, selector) {
  const elements = document.querySelectorAll(selector);
  if (elements.length > 0) {
    import(`./${moduleName}`).then(({ default: Module }) => {
      for (var i = 0, c = elements.length; i < c; i++) {
        new Module(elements[i]);
      }
    });
  }
}
