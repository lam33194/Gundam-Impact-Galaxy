import { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./Signup.scss";
import { signup } from "../services/AuthService";

function Signup() {

  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: "",
    phone: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { id, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [id]: value,
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const response = await signup(formData);
      console.log("Đăng ký thành công:", response.data);
      alert("Đăng ký thành công!");
      navigate('/login', {
        state: {
          email: formData.email,
          password: formData.password
        }
      });
    } catch (error) {
      console.error("Đăng ký thất bại:", error);
      alert("Đăng ký thất bại. Vui lòng thử lại.");
    }
  };

  return (
    <div className="signup-container d-flex m-auto row col-lg-8">
      <div className="left d-flex flex-column col-lg-8 p-5 gap-3">
        <form
          onSubmit={handleSubmit}
        >
          <div className="form-group">
            <h4 className="fw-bold mb-4">Đăng ký tài khoản</h4>

            <div className="mb-3">
              <label htmlFor="name" className="form-label fw-bold">
                Họ tên
              </label>
              <input
                type="text"
                id="name"
                value={formData.name}
                className="form-control form-control-lg"
                placeholder="Nhập họ và tên của bạn"
                onChange={handleChange}
              />
            </div>

            <div className="mb-3">
              <label htmlFor="phone" className="form-label fw-bold">
                Số điện thoại
              </label>
              <input
                type="tel"
                id="phone"
                value={formData.phone}
                className="form-control form-control-lg"
                placeholder="Nhập số điện thoại"
                onChange={handleChange}
              />
            </div>

            <div className="mb-3">
              <label htmlFor="email" className="form-label fw-bold">
                Email
              </label>
              <input
                type="email"
                id="email"
                value={formData.email}
                className="form-control form-control-lg"
                placeholder="Nhập email của bạn"
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
                value={formData.password}
                className="form-control form-control-lg"
                placeholder="Nhập mật khẩu của bạn"
                onChange={handleChange}
              />
            </div>

            <div className="mb-3">
              <label htmlFor="password_confirmation" className="form-label fw-bold">
                Nhập lại mật khẩu
              </label>
              <input
                type="password"
                id="password_confirmation"
                value={formData.password_confirmation}
                className="form-control form-control-lg"
                placeholder="Nhập lại mật khẩu của bạn"
                onChange={handleChange}
              />
            </div>

            <button type="submit" className="btn btn-lg btn-dark col-12 fw-bold text-uppercase">
              Đăng ký
            </button>
          </div>
        </form>
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
        <button className="btn btn-lg col-8"><a href="/login" style={{ 'color': '#fff' }}>Đăng nhập</a></button>
      </div>
    </div>
  );
}

export default Signup;