
var React = require('react');
var Note = React.createClass({
  render: function() {
    return (
      <div>Hello {this.props.name}!</div>
    )
  }
});
module.exports = Note;
