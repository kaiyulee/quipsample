var React = require('react');

var List = React.createClass({

    getInitialState: function() {
        return {data: []}
    },
    componentDidMount: function() {
        Ajax.get('list.json', {}, function(data){
            this.setState({data: data});
        }.bind(this))
    },
    render: function() {
        return (
            <div>
            <iframe src="http://im.lo/index.html?uid=1"></iframe>
            </div>
        );
    }
});
module.exports = List;
