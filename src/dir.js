var React = require('react');
var Item = require('./item');
var Ajax = require('ajax');
var Dir = React.createClass({
    getInitialState: function() {
        return {data: []};
    },
    componentDidMount: function() {
        Ajax.get('dirList.json', {}, function(data){
            this.setState({data: data});
        }.bind(this))
    },
    render: function() {
        return (
            <div className = "dir">
                <Item data = {this.state.data} />
            </div>
        );
    }
});
module.exports = Dir;
