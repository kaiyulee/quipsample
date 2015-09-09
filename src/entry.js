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
        return {
            isLogin: Cookies.get('login') == 'login' ? true : false
        };
    },
    isLogin: function() {
        var isLogin = Cookies.get('login') === 'login' ? true : false;
        if(isLogin) {
            this.setState({isLogin: true});
        }
    },
    dirList: function() {
        Ajax.get('data.json', {}, function(data){return data});
        return function(){return 'a'};
    },
    dirDelete: function () {

    },
    dirUpdate: function() {

    },
    render: function() {
        var props = {
        }

        if(!this.state.isLogin) {
            return ( <Login isLogin = {this.isLogin.bind(this)} /> )
        } else {
            var a = this.props.dirList;
            console.log(a);
            return (
                <Dir
                  dirDelte = {this.dirDelete.bind(this)}
                  dirList = {this.dirList}
                  dirUpdate = {this.dirUpdate.bind(this)}
                />
            )
        }
    }
});

React.render(
    <App />,
    document.getElementById('quip')
);
