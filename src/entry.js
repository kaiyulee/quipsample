var React = require('react');
var Cookies = require('cookies-js');
var Ajax = require('ajax');
var Login = require('./login');
var List  = require('./list');
var App = React.createClass({
    getInitialState: function() {
        return {
            isLogin: Cookies.get('login') == 'login' ? true : false
        }
    },
    isLogin: function() {
        console.log('a');
        var isLogin = Cookies.get('login') === 'login' ? true : false;
        if(isLogin) {
            this.setState({isLogin: true});
        }
    },
    render: function() {
        if(!this.state.isLogin) {
            return (<Login isLogin = {this.isLogin.bind(this)} />)
        } else {
            return (<List data = {this.state.data} />)
        }
    }
});

React.render(
    <App />,
    document.getElementById('react')
)
