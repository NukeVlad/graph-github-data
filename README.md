# Cài đặt

Tải kho https://github.com/hoaquynhtim99/graph-github-data/archive/master.zip về giải nén được thư mục `graph-github-data-master` đặt vào `localhost`.

Yêu cầu máy chủ:

- PHP >= 5.6
- SSL support

# Thống kê số commit

Mở file commit.php sửa

```php
$client->setCredentials('YourGithubUserEmail@mail.com', 'YourGithubPass');
```

Bằng email và mật khẩu đăng nhập github của bạn. Thay

```php
$from = 1483207200;
$to = 1514743200;
```

Bằng thời gian lấy thống kê commit, sau đó chạy http://localhost/graph-github-data-master/commit.php để xem commit

Chú ý chuẩn hóa tác giả nếu muốn tại:

```php
$Array_Link = array(
    .....
);
```

Nếu `$commit['committer'] == 'web-flow'` tức đây là các commit được thực hiện trực tiếp trên Github hoặc các lệnh Merge trên github

# Thống kê số Issue

Mở file issue.php sửa

```php
$client->setCredentials('YourGithubUserEmail@mail.com', 'YourGithubPass');
```

Bằng email và mật khẩu đăng nhập github của bạn. Thay

```php
$from = 1483207200;
$to = 1514743200;
```

Bằng thời gian lấy thống kê issue, sau đó chạy http://localhost/graph-github-data-master/issue.php để xem issue
