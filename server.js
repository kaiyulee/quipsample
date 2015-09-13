var webpack = require('webpack');
var WebpackDevServer = require('webpack-dev-server');
var config = require('./webpack.config');
var port = '80';
var server = 'http://learn-swl.me';

new WebpackDevServer(webpack(config), {
    publicPath: config.output.publicPath,
    hot: true,
    noInfo: false,
    historyApiFallback: true
}).listen(port, server, function(err, result){
    if(err){
        console.log(err);
        return;
    }
    console.log('Start webpack server at port ' + port);
});
