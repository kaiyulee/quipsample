var React = require('react');
var Ajax  = require('ajax');
var Sect  = require('./Sect');
var Note = React.createClass({
    getInitialState: function() {
        return {
            data: [],
            lock: null
        }
    },
    componentDidMount: function() {
        Ajax.get('note.json', {}, function(data){
            var data = eval(data);
            this.setState({data: data});
        }.bind(this))
    },
    addSect(sect) {
        this.state.data.splice(sect.turn, 0, sect);
        Ajax.get('note.json', {}, function(data){
           // console.log(data);
        });
        this.setState(this.state.data);
    },
    deleteSect(index) {
        this.state.data.splice(index, 1);
        this.setState({data: this.state.data});
        Ajax.post('note.json', {}, function(data){
            console.log(data);
        });
    },
    render: function() {
        var dataSec = eval(this.state.data);
        var sect = [];
        for(var i = 0; i < dataSec.length + 1; i++) {
            var id = dataSec[i] ? dataSec[i].id : '';
            var uid = dataSec[i] ? dataSec[i].uid : '';
            var docid = dataSec[i] ? dataSec[i].docid : '';
            var content = dataSec[i] ? dataSec[i].content : '';
            sect.push(
                <Sect
                    locked = {this.state.lock == i ? '1' : '0'}
                    addSect = {this.addSect.bind(this)}
                    deleteSect = {this.deleteSect.bind(this)}
                    key = {i}
                    turn = {i}
                    id={id}
                    uid={uid}
                    docid={docid}
                    content={content}
                />
            )
        }
        return (
            <div className = "quip">
                <header className = "header">
                    <span>返回</span>
                    <dl>
                        <dt>头像</dt>
                        <dd>名字</dd>
                    </dl>
                </header>
                <div id="note" >
                    {sect}
                </div>
            </div>
        )
    }
});
module.exports = Note;
