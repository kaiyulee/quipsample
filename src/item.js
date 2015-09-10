var React = require('react');
var Item = React.createClass({
    handlerMouseOver: function(){
        React.findDOMNode(this.refs.deleteBtn).style.display = "inline";
    },
    handlerMouseOut: function(){
        React.findDOMNode(this.refs.deleteBtn).style.display = "none";
    },
    handlerDelete(){
        this.props.deleteTodo(this.props.index);
    },
    render: function() {
        var dataJson = eval(this.props.data);
        var list = dataJson.map(function(data) {
            return (
                <li className = "item" data-id = {data.id} data-pid = {data.dirid} >
                    <h2>{data.name}</h2>
                </li>
            );
        });
        return (
            <ul className = "list">
                  {list}
            </ul>
        );
    }
});
module.exports = Item;
