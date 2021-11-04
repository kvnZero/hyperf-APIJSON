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
3. ✅支持运算符 {}, <>, }{@, $, %, ~

待完成：子查询, 复杂查询, 嵌套查询, 存储过程调用，远程函数，权限

### 如何使用 

拉取该项目, 配置mysql数据库, 配置文件路径：`config\autoload\databases.php`

然后在项目目录执行：(需机器有：docker)

```shell
# 打包镜像
docker build -t hyperf-apijson:v1 .
#启动容器 映射本地9501端口
docker run -dit --name hyperf-apijson -p 9501:9501 hyperf-apijson:v1
```
