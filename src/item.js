var React = require('react');
var Ajax = require('ajax');
var Item = React.createClass({
    handlerMouseOver: function(){
        React.findDOMNode(this.refs.deleteBtn).style.display = "inline";
    },
    handlerMouseOut: function(){
        React.findDOMNode(this.refs.deleteBtn).style.display = "none";
    },
    handlerDelete: function() {
        Ajax.post('/document/soft_del', {doc_id: 1}, function(res) {
            var r = eval(res);
            if (r.code > 0) {
                // fail
                console.log(r.msg);
            } else {
                // success
                console.log('success');
            }
        },bind(this));
    },
    handlerTitleUpdate: function(event) {
        Ajax.get('/document/update', {doc_id: 1}, function(res) {
            var r = eval(res);
            switch (r.code) {
                case '1':
                    console.log('error occured!');
                    break;
                case '1024':
                    console.log('locked');
                    break;
                default :
                    console.log('success');
                    React.findDOMNode(this.refs.title).innerHTML = r['data'];
            }
        }.bind(this));
    },
    render: function() {
        return (
            <li className = "item"
                onMouseOver = {this.handlerMouseOver.bind(this)}
                onMouseOut  = {this.handlerMouseOut.bind(this)}
            >
                <h2 contentEditable="true"
                    onClick = {this.handlerTitleUpdate.bind(this)}
                    ref = 'title'
                    >
                    {this.props.name}
                </h2>
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

