var React = require('react');
var Sect = React.createClass({
    getInitialState: function() {
        return {
            lock: (this.props.locked * 1 == 1) ? true : false
        }
    },
    handlerClick: function(event) {
        var el = React.findDOMNode(this.refs.sec);
        var locked = el.getAttribute('data-uid');
        if(locked * 1 === 6 || locked.length <= 0) {
            this.setState({lock: true});
            el.setAttribute('data-uid', '7777');
            el.focus();
        }
    },
    handlerFocus: function(event) {
        var el = React.findDOMNode(this.refs.sec);
            el.setAttribute('data-uid', '7777');
    },
    handlerBlur: function(event) {
        var el = React.findDOMNode(this.refs.sec);
            el.setAttribute('data-uid', '');
            this.setState({lock: false});
            el.setAttribute('contenteditable', 'false');
    },
    handlerBack: function(event) {
        if(event.keyCode === 8) {
            event.returnValue=false;
            var a = React.findDOMNode(this.refs.back);
        }
    },
    handlerPress: function() {
        if(event.keyCode === 13 || event.charCode === 13) {
            event.preventDefault();
        }
    },
    handlerKeyUp: function(event) {
        if(event.keyCode === 13 || event.charCode === 13) {
            event.preventDefault();
        }
    },
    handlerDown: function(event) {
        if(event.keyCode === 13 || event.charCode === 13) {
            event.preventDefault();
            var el = React.findDOMNode(this.refs.sec);
            var newSect = {
                id: el.getAttribute('data-id'),
                uid: el.getAttribute('data-uid'),
                docid: el.getAttribute('data-docid'),
                content: '',
                turn: el.getAttribute('data-turn') * 1 + 1
            }
            this.props.addSect(newSect);
            var elFocus = document.getElementById('note').getElementsByTagName('section')[newSect.turn];
            el.setAttribute('contenteditable', 'false');
            el.setAttribute('data-uid', '');
            elFocus.setAttribute('contenteditable', 'true');
            elFocus.focus();
        }
    },
    render: function() {
        var able = this.state.lock ? true : 'false';
        return (
            <section
                contentEditable = {able}
                ref = 'sec'
                data-turn = {this.props.turn}
                data-lock = {this.props.locked}
                data-id = {this.props.id}
                data-uid = {this.props.uid}
                data-docid = {this.props.docid}
                onKeyPress = {this.handlerPress.bind(this)}
                onKeyDown = {this.handlerDown.bind(this)}
                onKeyUp = {this.handlerKeyUp.bind(this)}
                onFocus = {this.handlerFocus.bind(this)}
                onBlur = {this.handlerBlur.bind(this)}
                onClickCapture = {this.handlerClick.bind(this)}
            >{this.props.content}
            </section>
        )
    }
});
module.exports = Sect;

