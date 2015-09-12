var React = require('react');
var Ajax = require('ajax');
var Cookies = require('cookies-js');

var Login = React.createClass({
    handleClick: function(event) {
        var userName = React.findDOMNode(this.refs.userName).value;
        var passWord = React.findDOMNode(this.refs.passWord).value;
        function isLogin(callback) {
            Ajax.get('login.json', {username: userName, password: passWord}, function(data) {
                var data = eval(data);
                if (data.true == true) {
                    Cookies.set('login', 'login');
                    Cookies.set('user', '1');
                    callback();
                } else {
                    alert('密码错误');
                }
            });
        }
        isLogin(this.props.isLogin);
    },
    render: function() {
        return (
            <div className = "login">
                <h1>SIGN IN</h1>
                <input placeholder = "Enter your username" type = "text" ref = "userName"></input>
                <input placeholder = "Enter your password" type = "password" ref = "passWord"></input>
                <div className = "enter" onClick = {this.handleClick}>SIGN IN</div>
            </div>
        );
    }
});

module.exports = Login;
