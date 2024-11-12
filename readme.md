<h1 style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 2.5rem ">
    Dự án back-end php server
</h1>

<div style="text-align:center;">
    <img src="https://cdn-icons-png.flaticon.com/128/9277/9277351.png" width="50" height="50"/>
    <img src="https://cdn-icons-png.flaticon.com/128/919/919840.png" width="50" height="50" style="margin-left: 50px"/>
</div>

## URL Dự án
- https://ltwbe.hcmutssps.id.vn/
- https://ltw-latest.onrender.com/

## Cách biên dịch dự án
#### Yêu cầu:
```aiignore
Môi trường linux hoặc window đã cài commposer
Cài composer: tải setup với window hoặc sudo apt install composer với wsl
Chạy composer install để cài đặt thư viên
Composer start để chạy chương trình
Đảm bảo cổng 8080 không bị chiếm bởi chương trình khác
```

## Cấu trúc API


#### /auth: xác thực người dùng

- /auth/login: đăng nhập, kết quả trả về là token nếu nhập đúng mật khẩu
    ```aiignore
    Yêu cầu requestBody:
    {
      "username": "test",
      "password": "123"
    }
    ```

- /auth/register: đăng kí, kết quả trả về là token nếu đúng định dạng
    ```aiignore
    Yêu cầu requestBody:
    {
      "username": "heheboi2e",
      "password": "12312312a",
      "email": "heheboi2@outlook.com"
    }
    ```

- /auth/refreshToken: yêu cầu làm mới token, kết quả trả về là token nếu token cũ hợp lệ

### /api: Chức năng chung của người dùng sau khi đăng nhập, yêu cầu token để xác thực

- /api/changeDisplayName: Đổi tên hiển thị
    ```aiignore
    Yêu cầu requestParam:
    ? display_name = <your_name>
    ```

- /api/changeEmail: Đổi email của người dùng
    ```aiignore
    Yêu cầu requestParam:
    ? email = <your_email>
    ```

- /api/changePassword: Đổi mật khẩu của người dùng
    ```aiignore
    Yêu cầu requestBody:
    {
        "password": "new_password"
    }
    ```

- /api/deposit: Nạp tiền vào tài khoản
    ```aiignore
    Yêu cầu requestParam:
    ? amount = <số dương bất kì>
    ```

- /api/getHistoryPayment: Lấy lịch sử thanh toán
    ```aiignore
    Yêu cầu requestParam:
    ? offset = <số nguyên lớn hơn 0>
    ```

- /api/getUserInfo: Lấy thông tin cá nhân

### /api/manager: Api dành cho quản lí, yếu cầu đã xác thực token và role = manager

- /api/manager/deleteUser: Xóa user ra khỏi hệ thống
    ```aiignore
    Yêu cầu requestParam:
    ? username = <tên user>
    ```

- /api/manager/getAllHistoryPayment: Xóa user ra khỏi hệ thống
    ```aiignore
    Yêu cầu requestParam:
    ? offset = <số nguyên lớn hơn 0>
    ```

- /api/manager/getAllUser: Lấy danh sách user
    ```aiignore
    Yêu cầu requestParam:
    ? offset = <số nguyên lớn hơn 0>
    ```



