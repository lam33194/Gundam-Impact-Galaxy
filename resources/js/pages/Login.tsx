import { useLocation } from "react-router-dom";
import "./Login.scss";
import { useEffect, useState } from "react";

function Login() {

  const location = useLocation();
  const [formData, setFormData] = useState({
    email: location.state?.email || "",
    password: location.state?.password || ""
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { id, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [id]: value
    }));
  };

  useEffect(() => {
    if (location.state?.email && location.state?.password) {
      setFormData({
        email: location.state.email,
        password: location.state.password
      });
    }
  }, [location]);

  return (

    <div className="login-container d-flex m-auto row col-lg-8">
      <div className="left d-flex flex-column col-lg-8 p-5 gap-3">
        <div className="form-group">
          <h4 className="fw-bold mb-4">Đăng nhập tài khoản</h4>
          <div className="mb-3">
            <label htmlFor="email" className="form-label fw-bold">
              Email
            </label>
            <input
              type="email"
              id="email"
              className="form-control form-control-lg"
              placeholder="Email"
              value={formData.email}
              onChange={handleChange}
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
              placeholder="Mật khẩu"
              value={formData.password}
              onChange={handleChange}
            />
          </div>
          <button type="submit" className="btn btn-lg btn-dark col-12">
            Đăng nhập
          </button>
        </div>
        <div className="d-flex align-items-center justify-content-center">
          <div className="line col-4"></div>
          <span className="col-4 text-center">Hoặc đăng nhập bằng</span>
          <div className="line col-4"></div>
        </div>

        <div className="row justify-content-center gap-2 oauth">
          <button className="btn btn-lg btn-primary btn-block col-md-3 col-sm-6 mb-2 d-flex align-items-center justify-content-center">
            <i className="fab fa-facebook-f me-2"></i> <span>Facebook</span>
          </button>


          <button className="btn btn-lg btn-danger btn-block col-md-3 col-sm-6 mb-2 d-flex align-items-center justify-content-center">
            <i className="fab fa-google me-2"></i> <span>Google</span>
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
        <button className="btn btn-lg col-7"><a href="/signup" style={{ 'color': '#fff' }}>Đăng ký</a></button>
      </div>
    </div>
  );
}

export default Login;