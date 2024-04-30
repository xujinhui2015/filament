# Filament 后台脚手架

## 上线指南

```bash
# 安装包管理
composer install
# 创建软连接
php artisan storage:link
```

## 七牛云对象存储配置

```bash
# 安装网址：
https://github.com/overtrue/laravel-filesystem-qiniu
# 安装完毕后再env文件配置即可
FILAMENT_FILESYSTEM_DISK=qiniu
```
