var React = require('react');
var Item = React.createClass({
    handlerMouseOver: function(){
        React.findDOMNode(this.refs.deleteBtn).style.display = "inline";
    },
    handlerMouseOut: function(){
        React.findDOMNode(this.refs.deleteBtn).style.display = "none";
    },
    handlerDelete(){
    },
    render: function() {
        return (
            <li className = "item"
                onMouseOver = {this.handlerMouseOver.bind(this)}
                onMouseOut  = {this.handlerMouseOut.bind(this)}
            >
                <h2 contentEditable="true">{this.props.name}</h2>
                <span
                    ref = "deleteBtn"
                    onClick = {this.handlerDelete.bind(this)}
                    style = {{'display': 'none'}}
                    data-id = {this.props.id}
                    data-dirid = {this.props.dirid}
                >delete</span>
            </li>
        );
    }
});
module.exports = Item;

