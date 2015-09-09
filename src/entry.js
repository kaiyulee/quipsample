// React
var React = require('react');
// Cookies
var Cookies = require('cookies-js');
// Ajax
var Ajax = require('ajax');
// Images upload base64
require('./processImg');
// CSS
require('./style.scss');
// login
var Login = require('./login');
// dir
var Dir = require('./dir');
// note
var Note = require('./note');
// Info
var Info = require('./info');

var App = React.createClass({
    getInitialState: function() {
        return {isLogin: false};
    },
    handleClick: function(event) {
        this.setState({isLogin: ture});
    },
    render: function() {
        var text = this.state.isLogin ? 'like' : 'haven\'t liked';
        return (
            <div>
                <Login />
                <Dir />
            </div>
        );
    }
});

React.render(
    <App />,
    document.getElementById('quip')
);
