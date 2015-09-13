var Chat = React.createClass({

    getDefaultProps : function () {

        return {
            url : "http://im.lo/index.html?uid=" + uid
        };
    },

    render : function() {
        return (
            <div>
                <iframe src={this.props.url} width="100%" height="530px"></iframe>
            </div>
        );
    }
});

module.exports = Chat;

