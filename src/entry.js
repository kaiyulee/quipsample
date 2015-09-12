var React = require('react');
var Cookies = require('cookies-js');
var Ajax = require('ajax');
var Login = require('./login');
var List  = require('./list');
var Note  = require('./note');
var App = React.createClass({
    getInitialState: function() {
        return {
            isLogin: Cookies.get('login') == 'login' ? true : false
        }
    },
    isLogin: function() {
        var isLogin = Cookies.get('login') === 'login' ? true : false;
        if(isLogin) {
            this.setState({isLogin: true});
        }
    },
    render: function() {
        if(!this.state.isLogin) {
            return (
                <div>
                    <Login isLogin = {this.isLogin.bind(this)} />
                    <List data = {this.state.data} />
                </div>
            )
        } else {
            return (<Note />)
        }
    }
});

React.render(
    <App />,
    document.getElementById('react')
)
