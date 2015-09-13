var React = require('react');
var Cookie = require('cookies-js');
var Ajax  = require('ajax');
var Item  = require('./item');
var Chat = require('./chat');

var Avatar = React.createClass({

    getDefaultProps : function () {
        return {
            username : '',
            avatar : ''
        };
    },

    render: function(){
        return (
            <dl>
                <dt> <img src={this.props.data.avatar} /></dt>
                <dd> {this.props.data.username}</dd>
            </dl>
        );
    }
});

var Chat = React.createClass({

    getDefaultProps : function () {

        return {
            url : ''
        };
    },

    render : function() {
        this.props.url = 'http://im.lo/index.html?uid=' + this.props.data.uid;
        return (
            <div>
                <iframe src={this.props.url} width="100%" height="530px"></iframe>
            </div>
        );
    }
});

var List = React.createClass({
    getInitialState: function() {
        return {data: []}
    },

    componentDidMount: function() {
        Ajax.get('list.json', {}, function(data){
            this.setState({data: data});
        }.bind(this));
    },

    render: function() {

        var dataList = eval(this.state.data);
        var item = [];

        for(var i = 0; i < dataList.length; i++) {
            item.push(<Item id= {dataList[i].id} name = {dataList[i].name} dirid = {dataList[i].dirid}/>)
        }

        return (
            <div className = "quip">
                <header className = "header">
                    <span>返回</span>
                    <span>添加</span>
                    <Avatar data={this.props.data} />
                </header>
                <ul className = "list">
                    {item}
                </ul>
                <Chat data={this.props.data} />
            </div>
        );
    }
});
module.exports = List;
