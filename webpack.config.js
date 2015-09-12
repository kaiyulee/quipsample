var webpack = require('webpack');
module.exports = {
    entry: {
        app: [
            'webpack-dev-server/client?http://127.0.0.1:7777',
            'webpack/hot/only-dev-server',
            './src/entry.js'
        ]
        /*,
        dom: [
            'webpack-dev-server/client?http://127.0.0.1:7777',
            'webpack/hot/only-dev-server',
            './js/dom.js'
        ]*/
    },
    output: {
        path: __dirname + '/www',
        publicPath: '/www',
        filename: '[name].js'
    },
    module: {
        loaders: [
            {test: /\.(js|jsx)$/, exclude: /node_modules/, loader: 'react-hot!jsx-loader?harmony' },
            {test: /\.css$/, loader: 'style!css' },
            {test: /\.scss$/, loader: 'style!css!sass' },
            {test: /\.woff$/, loader: 'url?limit=100000' }
        ]
    },
    resolve: {
        extensions: ['', '.js', '.jsx', '.woff', '.png', '.jpg']
    },
    plugins: [
        new webpack.HotModuleReplacementPlugin(),
        new webpack.NoErrorsPlugin()
    ]
};
