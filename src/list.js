var React = require('react');
var Ajax  = require('ajax');
var Item  = require('./item');
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
                    <dl>
                        <dt>头像</dt>
                        <dd>名字</dd>
                    </dl>
                </header>
                <ul className = "list">
                    {item}
                </ul>
            </div>
        );
    }
});
module.exports = List;
