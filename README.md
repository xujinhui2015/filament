# Filament 后台脚手架

## 上线指南

```bash
# 安装包管理
composer install
# 创建软连接
php artisan storage:link
# 创建配置文件并配置好数据库信息
cp .\.env.example .env
# 生成APP_KEY
php artisan key:generate
# 执行迁移
php artisan migrate
# 生成超级管理员账号,由于是手机号登录的，还需要手动去users表设置下phone字段
php artisan make:filament-user
# 配置权限,并将刚刚生成的账号设置为超级管理员
php artisan shield:generate --all
php artisan shield:super-admin --user=1
# 生成模拟数据（可选）
php artisan db:seed --class=UserSeeder # 生成会员数据以及积分余额记录
```

## 七牛云对象存储配置

```bash
# 安装网址：
https://github.com/overtrue/laravel-filesystem-qiniu
# 安装完毕后再env文件配置即可
FILAMENT_FILESYSTEM_DISK=qiniu
```
