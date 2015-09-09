var React = require('react');
var Info = React.createClass({
    handleClick: function(event) {
        var userName = React.findDOMNode(this.refs.userName).value;
        var passWord = React.findDOMNode(this.refs.passWord).value;
        console.log("userName:" + userName, " passWord:" + passWord);
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

module.exports = Info;
