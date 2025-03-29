import "./Signup.scss";

function Signup() {
  return (
    <div className="signup-container d-flex m-auto row col-lg-8">
      <div className="left d-flex flex-column col-lg-8 p-5 gap-3">
        <div className="form-group">
          <h4 className="fw-bold mb-4">Đăng ký tài khoản</h4>

          <div className="mb-3">
            <label htmlFor="ho" className="form-label fw-bold">
              Họ
            </label>
            <input
              type="text"
              id="ho"
              className="form-control form-control-lg"
              placeholder="Nhập họ của bạn"
            />
          </div>

          <div className="mb-3">
            <label htmlFor="ten" className="form-label fw-bold">
              Tên
            </label>
            <input
              type="text"
              id="ten"
              className="form-control form-control-lg"
              placeholder="Nhập tên của bạn"
            />
          </div>

          <div className="mb-3">
            <label htmlFor="phone" className="form-label fw-bold">
              Số điện thoại
            </label>
            <input
              type="tel"
              id="phone"
              className="form-control form-control-lg"
              placeholder="Nhập số điện thoại"
            />
          </div>

          <div className="mb-3">
            <label htmlFor="email" className="form-label fw-bold">
              Email
            </label>
            <input
              type="email"
              id="email"
              className="form-control form-control-lg"
              placeholder="Nhập email của bạn"
            />
          </div>

          <div className="mb-3">
            <label htmlFor="password" className="form-label fw-bold">
              Mật khẩu
            </label>
            <input
              type="password"
              id="password"
              className="form-control form-control-lg"
              placeholder="Nhập mật khẩu của bạn"
            />
          </div>

          <button type="submit" className="btn btn-lg btn-dark col-12 fw-bold text-uppercase">
            Đăng ký
          </button>
        </div>

        <div className="d-flex align-items-center justify-content-center">
          <div className="line col-4"></div>
          <span className="col-4 text-center">Hoặc đăng nhập bằng</span>
          <div className="line col-4"></div>
        </div>

        <div className="row justify-content-center gap-2 oauth">
          <button className="btn btn-lg btn-primary btn-block col-md-3 col-sm-6 mb-2 d-flex align-items-center justify-content-center">
            <i className="fab fa-facebook-f me-2"></i> Facebook
          </button>

          <button className="btn btn-lg btn-danger btn-block col-md-3 col-sm-6 mb-2 d-flex align-items-center justify-content-center">
            <i className="fab fa-google me-2"></i> Google
          </button>
        </div>
        <h6 className="text-center">
          Bạn quên mật khẩu nhấn <a href="">vào đây</a>
        </h6>
      </div>

      <div className="right d-flex flex-column col-lg-4 text-light p-5">
        <h4 className="mb-4 fw-bold">Quyền lợi với thành viên</h4>
        <ul className="mb-4">
          <li>
            <i className="fa-solid fa-fire me-2"></i>Dịch vụ đóng gói riêng
          </li>
          <li>
            <i className="fa-solid fa-fire me-2"></i>Tích điểm đặc quyền
          </li>
          <li>
            <i className="fa-solid fa-fire me-2"></i>Quà tặng bí mật
          </li>
          <li>
            <i className="fa-solid fa-fire me-2"></i>Chăm sóc khách hàng 1-1
          </li>
          <li>
            <i className="fa-solid fa-fire me-2"></i>Chi tiết hơn về chương
            trình hội viên, bạn có thể <a href="">xem tại đây</a>
          </li>
        </ul>
        <button className="btn btn-lg col-8"><a href="/login" style={{'color': '#fff'}}>Đăng nhập</a></button>
      </div>
    </div>
  );
}

export default Signup;