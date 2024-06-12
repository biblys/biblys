export default class Shortcut {
  constructor(props, manager) {
    this.props = {};
    this.props.name = props.name;
    this.props.url = props.url;
    this.props.icon = props.icon;
    this.props.class = props.class;
    this.props.subscription = props.subscription;

    this.manager = manager;
  }

  renderHtml() {
    // Create element
    this.element = document.createElement('div');
    this.element.classList.add('shortcut');
    this.element.innerHTML =
      '<span class="icon fa fa-' +
      this.props.icon +
      '"></span> ' +
      '<p>' +
      this.props.name +
      '</p>';

    // Create delete button
    var deleteButton = document.createElement('p');
    deleteButton.innerHTML =
      '<button class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></button>';
    this.element.appendChild(deleteButton);

    // Add delete event on button
    deleteButton.addEventListener(
      'click',
      function() {
        this.manager.remove(this);
      }.bind(this)
    );

    return this.element;
  }

  remove() {
    this.element.parentNode.removeChild(this.element);
  }
}
