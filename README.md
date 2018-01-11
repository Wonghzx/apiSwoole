# ApiSwoole Api 框架 ++++开发中++++
# 简介
ApiSwoole 是一款基于Swoole Server 原生协程 开发的常驻内存型PHP框架，摆脱传统PHP 传统的 PHP-FPM 运行模式在进程唤起和文件加载上带来的性能损失。而依旧维持Swoole Server 原有特性，支持同时混合监听HTTP、TCP、UDP、WebSocket协议，可异步，高可用的应用服务。

- 基于 Swoole 扩展
- 内置 HTTP 协程服务器
- 内置 WebSocket 协程服务器
- 内置 TCP、UDP、UnixSocket 协程服务器
- VC 分层设计 （目前没 M 模式）
- 高性能路由
- 全局容器注入
- 数据库 ORM （数据库迁移Laravel的ORM）
- 自定义用户进程
- Inotify 自动 Reload


# 环境要求
1. PHP 7.X
2. [Swoole 2.x](https://www.swoole.com/), 需开启协程和异步Redis
4. [Composer](https://getcomposer.org/)
5. [Inotify](https://pecl.php.net/package/inotify) (自动 Reload 服务 可选 )++在开发过程中经常需要更新文件，由于Swoole常驻内存的特性，文件在框架启动时已经载入了内存，当文件被修改时需要手动重启服务


# 配置
复制项目根目录的 `.env.example` 并命名为 `.env`
