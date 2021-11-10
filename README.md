<h1 align="center" style="text-align:center;">
  APIJSON
</h1>

<p align="center">零代码、热更新、全自动 ORM 库<br />🚀 后端接口和文档零代码，前端(客户端) 定制返回 JSON 的数据和结构</p>

<p align="center" >
  <a href="https://github.com/APIJSON/APIJSON-Demo/tree/master/MySQL"><img src="https://img.shields.io/badge/MySQL-5.7%2B-brightgreen.svg?style=flat"></a>
</p>
<p align="center" >
  <img src="https://img.shields.io/badge/PHP-8.0%2B-brightgreen.svg?style=flat"></a>
</p>

<p align="center" >
  <img src="https://oscimg.oschina.net/oscnet/up-3299d6e53eb0534703a20e96807727fac63.png" />
</p>

---

APIJSON 是一种专为 API 而生的 JSON 网络传输协议 以及 基于这套协议实现的 ORM 库。<br />
为各种增删改查提供了完全自动化的万能 API，零代码实时满足千变万化的各种新增和变更需求。<br />
能大幅降低开发和沟通成本，简化开发流程，缩短开发周期。<br />
适合中小型前后端分离的项目，尤其是 初创项目、内部项目、低代码/零代码、小程序、BaaS、Serverless 等。<br />

通过万能的 API，前端可以定制任何数据、任何结构。<br />
大部分 HTTP 请求后端再也不用写接口了，更不用写文档了。<br />
前端再也不用和后端沟通接口或文档问题了。再也不会被文档各种错误坑了。<br />
后端再也不用为了兼容旧接口写新版接口和文档了。再也不会被前端随时随地没完没了地烦了。


### 官方仓库

https://github.com/Tencent/APIJSON/commits/master


### 开发进度

1. ✅最基本CRUD 包括批量写入 批量更新 批量删除
2. ✅支持@column @having @order @group
3. ✅支持运算符 {}, }{@, $, %, ~
4. ✅支持多表查询、一对一查询、一对多查询、数组内多对多查询
5. ✅支持数组内count/page
6. ✅支持debug标签可返回语句执行

待完成：子查询, 存储过程调用，远程函数，权限，标签，<b>单元测试（高优先）</b>


### 如何使用 

Postman文档（调试过程中保存）：https://documenter.getpostman.com/view/7711046/UVC3jStM

拉取该项目, 配置mysql数据库, 配置文件路径：`config\autoload\databases.php`

然后在项目目录执行：(需机器有：docker)

```shell
# 打包镜像
docker build -t hyperf-apijson:v1 .
#启动容器 映射本地9501端口
docker run -dit --name hyperf-apijson -p 9501:9501 hyperf-apijson:v1
```

如果需要进行开发调试，使用Hyperf的Docker环境

```shell
docker run -dit --name hyperf-apijson -v {项目目录}:/opt/www -p 9501:9501 hyperf/hyperf:8.0-alpine-v3.12-swoole
```

进入到docker环境执行 （如果composer下载不动 可以修改到阿里镜像源 Mac下建议本地开发）：
```shell
cd /opt/www
composer update
php bin/hyperf.php start
```