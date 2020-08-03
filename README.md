## 介绍

demo是采用PHP(7.3.8)、Swoole(4.5.2)、LaravelS(3.7.6)、Laravel(7.21.0)构建的, 别看当前这些版本都比较高, 但同样适用于低版本的laravel项目, 希望可以帮到学习swoole的小伙伴们

##适用人群

在laravel-s QQ群中很多人对laravel-s的文档有些地方不太清楚, 这个demo就是把文档中的样例全部写到这个里面了

<img src="https://github.com/jie295053415/laravels-demo/storage/images/start.png" width="100%">

## 使用

1.下载

```
$ git clone git@github.com:jie295053415/laravels-demo.git
```

2.composer

```
$ composer install
```

3. 将laravels-demo.com.conf文件配置到nginx中, laravels-demo.com.conf文件中的域名可以修改, 然后在电脑的hosts文件, win的位置: C:\Windows\System32\drivers\etc, 把虚拟域名重定向到本地

4.启动服务
```
$ php bin/laravels start 
```

项目是默认启动的是ws服务器的, 可以通过一些websocket客户端测试

##感谢

[laravel](https://github.com/laravel/laravel)
[swoole](https://github.com/swoole/swoole-src)
[laravel-s](https://github.com/hhxsv5/laravel-s)
[moell-peng/webim](https://github.com/moell-peng/webim)

##免责声明
本项目开源且免费，仅用于交流学习，本项目亦不承担任何责任

## 贊助

<img src="https://github.com/jie295053415/laravels-demo/storage/images/alipay.jpg" width="100%">

## License

[MIT license](https://opensource.org/licenses/MIT).
