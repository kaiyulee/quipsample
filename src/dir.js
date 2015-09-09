var React = require('react');
var Dir = React.createClass({
    render: function() {
        return (
            <div className = "dir">
                <ul className = "list">
                    <li className = "item" data-id = "" data-pid = "" data-name = "">
                        <h2>文件夹名字</h2>
                    </li>
                    <li className = "item" data-id = "" data-pid = "" data-name = "">
                        <h2>文件夹名字</h2>
                    </li>
                    <li className = "item" data-id = "" data-pid = "" data-name = "">
                        <h2>文件夹名字</h2>
                    </li>
                </ul>
            </div>
        );
    }
});
module.exports = Dir;
