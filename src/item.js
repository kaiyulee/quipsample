var React = require('react');
var Item = React.createClass({
    render: function() {
        return (
            <li className = "item" data-id = "" data-pid = "" data-name = "">
                <h2>文件夹名字</h2>
            </li>
        );
    }
});
module.exports = Item;
