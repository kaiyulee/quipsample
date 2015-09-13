var React = require('react');
var Cookie = require('cookies-js');
var Ajax = require('ajax');
var Login = require('./login');
var List  = require('./list');
var Note  = require('./note');

var App = React.createClass({

    getInitialState: function() {
        return {
            isLogin: Cookie.get('login') == 'login' ? true : false,
            user : {}
        }
    },

    isLogin: function() {
        var isLogin = Cookie.get('login') === 'login' ? true : false;
        if(isLogin) {
            this.setState({isLogin: true});
        }
    },

    render: function() {
        if(!this.state.isLogin) {
            return (
                <div>
                    <Login isLogin = {this.isLogin.bind(this)} />
                </div>
            )
        } else {
            var user = {
                uid : Cookie.get('uid'),
                username : Cookie.get('username'),
                avatar : Cookie.get('avatar')
            };
            return (
                <List data = {user} />
            )
            //return (<Note />)
        }
    }
});

React.render(
    <App />,
    document.getElementById('react')
)
