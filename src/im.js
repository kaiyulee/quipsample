var React = require('react');

var Chat = React.createClass({

    getDefaultProps : function () {
        var uid = Cookie.get('uid');

        return {
            url : "http://im.lo/index.html?uid=" + uid
        };
    },

    render : function() {
        return (
            <div>
                <iframe src={this.props.url} width="530px" height="530px"></iframe>
            </div>
        );
    }
});

module.exports = Chat;

