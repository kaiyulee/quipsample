var React = require('react');
var Request = require('ajax-request');
var Cookies = require('cookies-js');

var Login = React.createClass({
    handleClick: function(event) {
        var userName = React.findDOMNode(this.refs.userName).value;
        var passWord = React.findDOMNode(this.refs.passWord).value;

        function isLogin(callback) {
            Request({
                url : '/api/user',
                method : 'POST', 
                data : {username:userName, password:passWord}
            },
            function(err, res, body) {
                var body =  eval('(' + body + ')');
                console.log(body);

                if (body.code == 0) {
                    Cookies.set('login', 'login');
                    Cookies.set('uid', body.data.uid);
                    Cookies.set('user', body.data);
                    callback();
                } else {
                    alert('账号或密码错误');
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
